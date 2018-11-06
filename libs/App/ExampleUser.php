<?php
/** @package    Example::App */

/** import supporting libraries */
require_once("verysimple/Authentication/IAuthenticatable.php");
require_once("util/password.php");
require_once ("Conf/conf_db.php");

/**
 * The ExampleUser is a simple account object that demonstrates a simplistic way
 * to handle authentication.  Note that this uses a hard-coded username/password
 * combination (see inside the __construct method).
 * 
 * A better approach is to use one of your existing model classes and implement
 * IAuthenticatable inside that class.
 *
 * @package Example::App
 * @author ClassBuilder
 * @version 1.0
 */
class ExampleUser implements IAuthenticatable
{
	/**
	 * @var Array hard-coded list user/passwords.  initialized on contruction
	 */
	static $USERS;
	
	static $PERMISSION_ADMIN = 1;
	static $PERMISSION_USER = 2;
        
	
	public $Username = '';
        public $userlogin = '';
        public $permissao;
	
	/**
	 * Initialize the array of users.  Note, this is only done this way because the 
	 * users are hard-coded for this example.  In your own code you would most likely
	 * do a single lookup inside the Login method
	 */
	public function __construct()
	{       /*
		if (!self::$USERS)
		{
			self::$USERS = Array(
				"demo"=>password_hash("pass",PASSWORD_BCRYPT),
				"admin"=>password_hash("pass",PASSWORD_BCRYPT)
			);
		}
            * */
         
	}

	/**
	 * Returns true if the user is anonymous (not logged in)
	 * @see IAuthenticatable
	 */
	public function IsAnonymous()
	{
		return $this->Username == '';
	}
	
	/**
	 * This is a hard-coded way of checking permission.  A better approach would be to look up
	 * this information in the database or base it on the account type
	 * 
	 * @see IAuthenticatable
	 * @param int $permission
	 */
	public function IsAuthorized($permission)
	{
		//if ($this->Username == 'admin') return true;
                //if ($this->Username != '') return true;
                
		if($this->permissao == 3 && $this->Username != '') return true;
		if ($this->Username != '' && $permission == self::$PERMISSION_USER) return true;
		
		return false;
	}
	
	/**
	 * This login method uses hard-coded username/passwords.  This is ok for simple apps
	 * but for a more robust application this would do a database lookup instead.
	 * The Username is used as a mechanism to determine whether the user is logged in or
	 * not
	 * 
	 * @see IAuthenticatable
	 * @param string $username
	 * @param string $password
	 */
	public function Login($username,$password)
	{
                $un = utf8_encode(htmlspecialchars($username));
                $pass = utf8_encode(htmlspecialchars($password));
                $conexao = conn_mysql();
                $SQLSelect = 'SELECT * FROM user WHERE password=MD5(?) AND username=?';
                $operacao = $conexao->prepare($SQLSelect);					  
                $pesquisar = $operacao->execute(array($pass, $un));
                $resultados = $operacao->fetchAll();
                $conexao = null;
                
                if (count($resultados)!=1){   
                    return false;
                }
                $this->permissao = $resultados[0]['role_id'];
                $this->userlogin = $resultados[0]['username'];
     
                return $this->Username = $resultados[0]['name'];
                
                
                /*
		foreach (self::$USERS as $un=>$pw)
		{       
			if ($username == $un && password_verify($password,$pw))
			{
				$this->Username = $username;
				break;
			}
		}
		
		return $this->Username != '';
                 * 
                 */
                
    
    
                
	}
	
}

?>