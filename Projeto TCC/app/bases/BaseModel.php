<?php

namespace Tcc\App\Bases;

/**
 * Model ORM
 *
 *
 *  A simple database abstraction layer for PHP 5.3+ with very minor configuration required
 *
 * database table columns are auto detected and made available as public members of the class
 * provides CRUD, dynamic counters/finders on a database table
 * uses PDO for data access and exposes PDO if required
 * class members used to do the magic are preceeded with an underscore, be careful of column names starting with _ in your database!
 * requires php >=5.3 as uses "Late Static Binding" and namespaces
 *
 *
 * @property string $created_at optional datatime in table that will automatically get updated on insert
 * @property string $updated_at optional datatime in table that will automatically get updated on insert/update
 *
 * @package default
 */

/**
 * Class Model
 *
 * @package Freshsauce\Model
 */
class BaseModel
{

    // Class configuration

    /**
     * @var \PDO
     */
    public static $_db; /* Todos os modelos herdam esta conexão ao banco de dados mas pode ser substituida em uma subclasse, chamando subClass::connectDB(...)
    A subclasse também deve redeclarar public static $_db;
    */

    /**
     * @var \PDOStatement[]
     */
    protected static $_stmt = array(); // Cache de declarações preparadas

    /**
     * @var string
     */
    protected static $_identifier_quote_character; // Caractere usado para citar nomes de tabelas e colunas

    /**
     * @var array
     */
    private static $_tableColumns = array(); // Colunas na tabela do banco de dados serão "populadas" automaticamente.
    // Os membros públicos dos objetos são criados para cada coluna de tabela dinâmicamente

    /**
     * @var \stdClass Todos os dados são armazenados aqui
     */
    protected $data;

    /**
     * @var \stdClass Se um um valor de campo foi alterado (tornado sujo/dirty), é armazenado aqui
     */
    protected $dirty;

    /**
     * @var string Nome da coluna de chave primária, definido apropriadamente na sua subclasse
     */
    protected static $_primary_column_name = 'id'; // Coluna de chave primária(primary key)

    /**
     * @var string Nome da tabela do banco de dados, definido apropriadamente na sua subclasse
     */
    protected static $_tableName = '_the_db_table_name_'; // database table name

    /**
     * Construtor de Modelo/Model
     *
     * @param array $data
     */
    public function __construct($data = array())
    {
        static::getFieldnames(); // Chamado apenas uma vez, na primera vez em que um objeto é criado. Chama a função que 
        $this->clearDirtyFields();
        if (is_array($data)) {
            $this->hydrate($data);
        }
    }

    /**
     * Checa se este objeto possui dados anexados
     *
     * @return bool
     */
    public function hasData()
    {
        return is_object($this->data);
    }


    /**
     * Retorna verdadeiro se dados presentes, se não lança uma Exception
     *
     * @return bool
     * @throws \Exception
     */
    public function dataPresent()
    {
        if (!$this->hasData()) {
            throw new \Exception('No data');
        }

        return true;
    }

    /**
     * Define campo em objeto de dado se não corresponde a um membro nativo do objeto
     * Inicializa o armazenamento de dados se não for um objeto
     *
     * @param string $name
     * @param mixed  $value
     *
     * @return void
     */
    public function __set($name, $value)
    {
        if (!$this->hasData()) {
            $this->data = new \stdClass();
        }
        $this->data->$name = $value;
        $this->markFieldDirty($name);
    }

    /**
     * Marca o campo como "sujo"(dirty), portanto isto será definido em inserções e atualizações
     *
     * @param string $name
     */
    public function markFieldDirty($name)
    {
        $this->dirty->$name = true; // Campo se tornou "sujo"(dirty)
    }

    /**
     * Retorna verdadeiro se preenchido como dirty, se não, falso
     *
     * @param string $name
     *
     * @return bool
     */
    public function isFieldDirty($name)
    {
        return isset($this->dirty->$name) && ($this->dirty->$name == true);
    }

    /**
     * Redefine quais campos foram considerados dirty, ie.(ou seja), foram alterados sem serem salvos no banco de dados 
     */
    public function clearDirtyFields()
    {
        $this->dirty = new \stdClass();
    }

    /**
     * Tenta e obtém o membro objeto do objeto de dados
     * se não corresponder a um membro nativo do objeto
     *
     * @param string $name
     *
     * @return mixed
     * @throws \Exception
     */
    public function __get($name)
    {
        if (!$this->hasData()) {
            throw new \Exception("data property=$name has not been initialised", 1);
        }

        if (property_exists($this->data, $name)) {
            return $this->data->$name;
        }

        $trace = debug_backtrace();
        throw new \Exception(
            'Undefined property via __get(): ' . $name .
            ' in ' . $trace[0]['file'] .
            ' on line ' . $trace[0]['line'],
            1
        );
    }

    /**
     * Testa a existencia do membro objeto do objeto de dados
     * se não corresponder ao membro nativo do objeto
     *
     * @param string $name
     *
     * @return bool
     */
    public function __isset($name)
    {
        if ($this->hasData() && property_exists($this->data, $name)) {
            return true;
        }

        return false;
    }

    /**
     * Define a conexão com o banco de dados para essa e todas as subclasses usarem
     * se uma subclasse substituir $_db, ela pode ter a própria conexão com o banco de dados, se necessário
     * os parâmetros são como em new PDO(...)
     * define PDO para lançar "excessões"(Exceptions) em caso de erro
     *
     * @param string $dsn
     * @param string $username
     * @param string $password
     * @param array  $driverOptions
     *
     * @throws \Exception
     */
    public static function connectDb($dsn, $username, $password, $driverOptions = array())
    {
        static::$_db = new \PDO($dsn, $username, $password, $driverOptions);
        static::$_db->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION); // Define "tratamento de erros"(Errorhandling) para "exceção"(Exception)
        static::_setup_identifier_quote_character();
    }

    /**
     * Detecta e inicializa o caractere usado para citar identificadores
     * (nomes de tabelas, nomes de colunas, etc.)
     *
     * @return void
     * @throws \Exception
     */
    public static function _setup_identifier_quote_character()
    {
        if (is_null(static::$_identifier_quote_character)) {
            static::$_identifier_quote_character = static::_detect_identifier_quote_character();
        }
    }

    /**
     * Retorna o caractere correto usado para citar identificadores
     * (nomes de tabelas, nomes de colunas, etc.) verificando o driver sendo usado pelo PDO.
     *
     * @return string
     * @throws \Exception
     */
    protected static function _detect_identifier_quote_character()
    {
        switch (static::getDriverName()) {
            case 'pgsql':
            case 'sqlsrv':
            case 'dblib':
            case 'mssql':
            case 'sybase':
                return '"';
            case 'mysql':
            case 'sqlite':
            case 'sqlite2':
            default:
                return '`';
        }
    }

    /**
     * Retorna o nome do driver para a conexão de base de dados atual
     *
     * @return string
     * @throws \Exception
     */
    protected static function getDriverName()
    {
        if (!static::$_db) {
            throw new \Exception('No database connection setup');
        }
        return static::$_db->getAttribute(\PDO::ATTR_DRIVER_NAME);
    }

    /**
     * Cita uma string que é usada como identificadorQuote a string that is used as an identifier
     * (nomes de tabelas, nomes de colunas, etc.). 
     * Esse metodo também pode lidar com identificadores separados por pontuação, eg.(por exemplo) 'table.column'
     *
     * @param string $identifier
     *
     * @return string
     */
    protected static function _quote_identifier($identifier)
    {
        $class = get_called_class();
        $parts = explode('.', $identifier);
        $parts = array_map(array(
            $class,
            '_quote_identifier_part'
        ), $parts);
        return join('.', $parts);
    }


    /**
     * Esse método performa a citação atual de uma única 
     * parte de um identificador, usando o caractere de citação do identificador
     * especificado na configuração (ou autodetectado)
     *
     * @param string $part
     *
     * @return string
     */
    protected static function _quote_identifier_part($part)
    {
        if ($part === '*') {
            return $part;
        }
        return static::$_identifier_quote_character . $part . static::$_identifier_quote_character;
    }

    /**
     * Obtém e armazena em cache na primeira chamada os nomes das colunas associadas à tabela atual
     *
     * @return array dos nomes das colunas para a tabela atual
     */
    protected static function getFieldnames()
    {
        $class = get_called_class();
        if (!isset(self::$_tableColumns[$class])) {
            $st                          = static::execute('DESCRIBE ' . static::_quote_identifier(static::$_tableName));
            self::$_tableColumns[$class] = $st->fetchAll(\PDO::FETCH_COLUMN);
        }
        return self::$_tableColumns[$class];
    }

    /**
     * Dados um array associativo dos pares de valores de chave
     * define o valor do membro correspondente se associado com uma coluna de tabela
     * ignora chaves que não correspondem a um nome de coluna de tabela
     *
     * @return void
     */
    public function hydrate($data)
    {
        foreach (static::getFieldnames() as $fieldname) {
            if (isset($data[$fieldname])) {
                $this->$fieldname = $data[$fieldname];
            } else if (!isset($this->$fieldname)) { // PDO pré popula os campos antes de chamar o constructor, então não nulifique a menos que não estejam definidos
                $this->$fieldname = null;
            }
        }
    }

    /**
     * define todos os membros para null que
     *
     * @return void
     */
    public function clear()
    {
        foreach (static::getFieldnames() as $fieldname) {
            $this->$fieldname = null;
        }
        $this->clearDirtyFields();
    }

    /**
     * @return array
     */
    public function __sleep()
    {
        return static::getFieldnames();
    }

    /**
     * @return array
     */
    public function toArray()
    {
        $a = array();
        foreach (static::getFieldnames() as $fieldname) {
            $a[$fieldname] = $this->$fieldname;
        }
        return $a;
    }

    /**
     * Obtém o registro com a a chave primária correspondente
     *
     * @param string $id
     *
     * @return Object
     */
    static public function getById($id)
    {
        return static::fetchOneWhere(static::_quote_identifier(static::$_primary_column_name) . ' = ?', array($id));
    }

    /**
     * Obtém o primeiro registro na tabela
     *
     * @return Object
     */
    static public function first()
    {
        return static::fetchOneWhere('1=1 ORDER BY ' . static::_quote_identifier(static::$_primary_column_name) . ' ASC');
    }

    /**
     * Obtém o último registro na tabela
     *
     * @return Object
     */
    static public function last()
    {
        return static::fetchOneWhere('1=1 ORDER BY ' . static::_quote_identifier(static::$_primary_column_name) . ' DESC');
    }

    /**
     * Encontra os registros com a chave primária correspondente
     *
     * @param string $id
     *
     * @return object[] de objetos para registros correspondentes
     */
    static public function find($id)
    {
        $find_by_method = 'find_by_' . (static::$_primary_column_name);
        static::$find_by_method($id);
    }

    /**
     * resolve chamadas para métodos estáticos não existentes, usado para implementar 
     * buscadores e contadores dinâmicos, ie.(ou seja),
     *  find_by_name('tom')
     *  find_by_title('a great book')
     *  count_by_name('tom')
     *  count_by_title('a great book')
     *
     * @param string $name
     * @param string $arguments
     *
     * @return mixed int|object[]|object
     * @throws \Exception
     */
    static public function __callStatic($name, $arguments)
    {
        // Nota: Valor de $name é case sensitive.
        if (preg_match('/^find_by_/', $name) == 1) {
            // Esse é um método dinâmico de find_by_{fieldname}
            $fieldname = substr($name, 8); // remove find by
            $match     = $arguments[0];
            return static::fetchAllWhereMatchingSingleField($fieldname, $match);
        } else if (preg_match('/^findOne_by_/', $name) == 1) {
            // Esse é um método dinâmico de findOne_by_{fieldname}
            $fieldname = substr($name, 11); // remove 'findOne_by_'
            $match     = $arguments[0];
            return static::fetchOneWhereMatchingSingleField($fieldname, $match, 'ASC');
        } else if (preg_match('/^first_by_/', $name) == 1) {
            // Esse é um método dinâmico de first_by_{fieldname}
            $fieldname = substr($name, 9); // remove 'first_by_'
            $match     = $arguments[0];
            return static::fetchOneWhereMatchingSingleField($fieldname, $match, 'ASC');
        } else if (preg_match('/^last_by_/', $name) == 1) {
            // Esse é um método dinâmico de last_by_{fieldname}
            $fieldname = substr($name, 8); // remove 'last_by_'
            $match     = $arguments[0];
            return static::fetchOneWhereMatchingSingleField($fieldname, $match, 'DESC');
        } else if (preg_match('/^count_by_/', $name) == 1) {
            // Esse é um método dinâmico de count_by_{fieldname}
            $fieldname = substr($name, 9); // remove 'find by'
            $match     = $arguments[0];
            if (is_array($match)) {
                return static::countAllWhere(static::_quote_identifier($fieldname) . ' IN (' . static::createInClausePlaceholders($match) . ')', $match);
            } else {
                return static::countAllWhere(static::_quote_identifier($fieldname) . ' = ?', array($match));
            }
        }
        throw new \Exception(__CLASS__ . ' not such static method[' . $name . ']');
    }

    /**
     * encontra uma correspondencia baseada em um único campo e critério de correspondência
     *
     * @param string       $fieldname
     * @param string|array $match
     * @param string       $order ASC|DESC
     *
     * @return object of calling class
     */
    public static function fetchOneWhereMatchingSingleField($fieldname, $match, $order)
    {
        if (is_array($match)) {
            return static::fetchOneWhere(static::_quote_identifier($fieldname) . ' IN (' . static::createInClausePlaceholders($match) . ') ORDER BY ' . static::_quote_identifier($fieldname) . ' ' . $order, $match);
        } else {
            return static::fetchOneWhere(static::_quote_identifier($fieldname) . ' = ? ORDER BY ' . static::_quote_identifier($fieldname) . ' ' . $order, array($match));
        }
    }


    /**
     * Encontra múltiplas correspondências baseado em um único campo e critério de correspondência
     *
     * @param string       $fieldname
     * @param string|array $match
     *
     * @return object[] de objetos da classe chamadora
     */
    public static function fetchAllWhereMatchingSingleField($fieldname, $match)
    {
        if (is_array($match)) {
            return static::fetchAllWhere(static::_quote_identifier($fieldname) . ' IN (' . static::createInClausePlaceholders($match) . ')', $match);
        } else {
            return static::fetchAllWhere(static::_quote_identifier($fieldname) . ' = ?', array($match));
        }
    }

    /**
     * para um dado array de parâmetros a ser passado para uma cláusula 'IN', 
     * retorna um placeholder de string
     *
     * @param array $params
     *
     * @return string
     */
    static public function createInClausePlaceholders($params)
    {
        return implode(',', array_fill(0, count($params), '?'));
    }

    /**
     * Retorna o número de linhas na tabela
     *
     * @return int
     */
    static public function count()
    {
        $st = static::execute('SELECT COUNT(*) FROM ' . static::_quote_identifier(static::$_tableName));
        return (int)$st->fetchColumn(0);
    }

    /**
     * Retorna uma contagem INTeira de linhas correspondentes
     *
     * @param string $SQLfragment   condições e agrupamento para aplicar
     *                              (à direita da palavra-chave WHERE) 
     * 
     * @param array  $params        parâmetros opcionais a serem escapados e
     *                              injetados na consulta SQL(Sintaxe padrão do PDO) 
     *
     * @return integer contagem de linhas que correspondem às condições
     */
    static public function countAllWhere($SQLfragment = '', $params = array())
    {
        $SQLfragment = self::addWherePrefix($SQLfragment);
        $st          = static::execute('SELECT COUNT(*) FROM ' . static::_quote_identifier(static::$_tableName) . $SQLfragment, $params);
        return (int)$st->fetchColumn(0);
    }

    /**
     * Se $SQLfragment não está vazio, insira como prefixo a palavra-chave WHERE
     *
     * @param string $SQLfragment
     *
     * @return string
     */
    static protected function addWherePrefix($SQLfragment)
    {
        return $SQLfragment ? ' WHERE ' . $SQLfragment : $SQLfragment;
    }

    /**
     * returns an array of objects of the sub-class which match the conditions
     *
     * @param string $SQLfragment conditions, sorting, grouping and limit to apply (to right of WHERE keywords)
     * @param array  $params      optional params to be escaped and injected into the SQL query (standrd PDO syntax)
     * @param bool   $limitOne    if true the first match will be returned
     *
     * @return mixed object[]|object of objects of calling class
     */
    static public function fetchWhere($SQLfragment = '', $params = array(), $limitOne = false)
    {
        $class       = get_called_class();
        $SQLfragment = self::addWherePrefix($SQLfragment);
        $st          = static::execute(
            'SELECT * FROM ' . static::_quote_identifier(static::$_tableName) . $SQLfragment . ($limitOne ? ' LIMIT 1' : ''),
            $params
        );
        $st->setFetchMode(\PDO::FETCH_ASSOC);
        if ($limitOne) {
            $instance = new $class($st->fetch());
            $instance->clearDirtyFields();
            return $instance;
        }
        $results = [];
        while ($row = $st->fetch()) {
            $instance = new $class($row);
            $instance->clearDirtyFields();
            $results[] = $instance;
        }
        return $results;
    }

    /**
     * returns an array of objects of the sub-class which match the conditions
     *
     * @param string $SQLfragment conditions, sorting, grouping and limit to apply (to right of WHERE keywords)
     * @param array  $params      optional params to be escaped and injected into the SQL query (standrd PDO syntax)
     *
     * @return object[] of objects of calling class
     */
    static public function fetchAllWhere($SQLfragment = '', $params = array())
    {
        return static::fetchWhere($SQLfragment, $params, false);
    }

    /**
     * returns an object of the sub-class which matches the conditions
     *
     * @param string $SQLfragment conditions, sorting, grouping and limit to apply (to right of WHERE keywords)
     * @param array  $params      optional params to be escaped and injected into the SQL query (standrd PDO syntax)
     *
     * @return object of calling class
     */
    static public function fetchOneWhere($SQLfragment = '', $params = array())
    {
        return static::fetchWhere($SQLfragment, $params, true);
    }

    /**
     * Delete a record by its primary key
     *
     * @return boolean indicating success
     */
    static public function deleteById($id)
    {
        $st = static::execute(
            'DELETE FROM ' . static::_quote_identifier(static::$_tableName) . ' WHERE ' . static::_quote_identifier(static::$_primary_column_name) . ' = ? LIMIT 1',
            array($id)
        );
        return ($st->rowCount() == 1);
    }

    /**
     * Delete the current record
     *
     * @return boolean indicating success
     */
    public function delete()
    {
        return self::deleteById($this->{static::$_primary_column_name});
    }

    /**
     * Delete records based on an SQL conditions
     *
     * @param string $where  SQL fragment of conditions
     * @param array  $params optional params to be escaped and injected into the SQL query (standrd PDO syntax)
     *
     * @return \PDOStatement
     */
    static public function deleteAllWhere($where, $params = array())
    {
        $st = static::execute(
            'DELETE FROM ' . static::_quote_identifier(static::$_tableName) . ' WHERE ' . $where,
            $params
        );
        return $st;
    }

    /**
     * do any validation in this function called before update and insert
     * should throw errors on validation failure.
     *
     * @return boolean true or throws exception on error
     */
    static public function validate()
    {
        return true;
    }

    /**
     * insert a row into the database table, and update the primary key field with the one generated on insert
     *
     * @param boolean $autoTimestamp      true by default will set updated_at & created_at fields if present
     * @param boolean $allowSetPrimaryKey if true include primary key field in insert (ie. you want to set it yourself)
     *
     * @return boolean indicating success
     */
    public function insert($autoTimestamp = true, $allowSetPrimaryKey = false)
    {
        $pk      = static::$_primary_column_name;
        $timeStr = gmdate('Y-m-d H:i:s');
        if ($autoTimestamp && in_array('created_at', static::getFieldnames())) {
            $this->created_at = $timeStr;
        }
        if ($autoTimestamp && in_array('updated_at', static::getFieldnames())) {
            $this->updated_at = $timeStr;
        }
        $this->validate();
        if ($allowSetPrimaryKey !== true) {
            $this->$pk = null; // ensure id is null
        }
        $set   = $this->setString(!$allowSetPrimaryKey);
        $query = 'INSERT INTO ' . static::_quote_identifier(static::$_tableName) . ' SET ' . $set['sql'];
        $st    = static::execute($query, $set['params']);
        if ($st->rowCount() == 1) {
            $this->{static::$_primary_column_name} = static::$_db->lastInsertId();
            $this->clearDirtyFields();
        }
        return ($st->rowCount() == 1);
    }

    /**
     * update the current record
     *
     * @param boolean $autoTimestamp true by default will set updated_at field if present
     *
     * @return boolean indicating success
     */
    public function update($autoTimestamp = true)
    {
        if ($autoTimestamp && in_array('updated_at', static::getFieldnames())) {
            $this->updated_at = gmdate('Y-m-d H:i:s');
        }
        $this->validate();
        $set             = $this->setString();
        $query           = 'UPDATE ' . static::_quote_identifier(static::$_tableName) . ' SET ' . $set['sql'] . ' WHERE ' . static::_quote_identifier(static::$_primary_column_name) . ' = ? LIMIT 1';
        $set['params'][] = $this->{static::$_primary_column_name};
        $st              = static::execute(
            $query,
            $set['params']
        );
        if ($st->rowCount() == 1) {
            $this->clearDirtyFields();
        }
        return ($st->rowCount() == 1);
    }

    /**
     * execute
     * convenience function for setting preparing and running a database query
     * which also uses the statement cache
     *
     * @param string $query  database statement with parameter place holders as PDO driver
     * @param array  $params array of parameters to replace the placeholders in the statement
     *
     * @return \PDOStatement handle
     */
    public static function execute($query, $params = array())
    {
        $st = static::_prepare($query);
        $st->execute($params);
        return $st;
    }

    /**
     * prepare an SQL query via PDO
     *
     * @param string $query
     *
     * @return \PDOStatement
     */
    protected static function _prepare($query)
    {
        if (!isset(static::$_stmt[$query])) {
            // cache prepared query if not seen before
            static::$_stmt[$query] = static::$_db->prepare($query);
        }
        return static::$_stmt[$query]; // return cache copy
    }

    /**
     * call update if primary key field is present, else call insert
     *
     * @return boolean indicating success
     */
    public function save()
    {
        if ($this->{static::$_primary_column_name}) {
            return $this->update();
        } else {
            return $this->insert();
        }
    }

    /**
     * Create an SQL fragment to be used after the SET keyword in an SQL UPDATE
     * escaping parameters as necessary.
     * by default the primary key is not added to the SET string, but passing $ignorePrimary as false will add it
     *
     * @param boolean $ignorePrimary
     *
     * @return array ['sql' => string, 'params' => mixed[] ]
     */
    protected function setString($ignorePrimary = true)
    {
        // escapes and builds mysql SET string returning false, empty string or `field` = 'val'[, `field` = 'val']...
        /**
         * @var array $fragments individual SQL assignments
         */
        $fragments = array();
        /**
         * @var array $params values in order to insert into SQl assignment fragments
         */
        $params = [];
        foreach (static::getFieldnames() as $field) {
            if ($ignorePrimary && $field == static::$_primary_column_name) {
                continue;
            }
            if (isset($this->$field) && $this->isFieldDirty($field)) { // Only if dirty
                if ($this->$field === null) {
                    // if empty set to NULL
                    $fragments[] = static::_quote_identifier($field) . ' = NULL';
                } else {
                    // Just set value normally as not empty string with NULL allowed
                    $fragments[] = static::_quote_identifier($field) . ' = ?';
                    $params[]    = $this->$field;
                }
            }
        }
        $sqlFragment = implode(", ", $fragments);
        return [
            'sql'    => $sqlFragment,
            'params' => $params
        ];
    }

    /**
     * convert a date string or timestamp into a string suitable for assigning to a SQl datetime or timestamp field
     *
     * @param mixed $dt a date string or a unix timestamp
     *
     * @return string
     */
    public static function datetimeToMysqldatetime($dt)
    {
        $dt = (is_string($dt)) ? strtotime($dt) : $dt;
        return date('Y-m-d H:i:s', $dt);
    }
}
