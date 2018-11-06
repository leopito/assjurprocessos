<?php
/** @package    Gerenciamentos de Processos::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/User.php");

/**
 * UserController is the controller class for the User object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Gerenciamentos de Processos::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class UserController extends AppBaseController
{

	/**
	 * Override here for any controller-specific functionality
	 *
	 * @inheritdocs
	 */
	protected function Init()
	{
		parent::Init();		
                
	}

	/**
	 * Displays a list view of User objects
	 */
	public function ListView()
	{
            // TODO: if authentiation is required for this entire controller, for example:
            $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm','Por favor, realize autenticação de usuário');    
            $this->Render();
	}
        
        public function SingleView(){
            $x = $this->GetRouter()->GetUrlParam('id');
            
            echo $x;
        }
        public function Exemplo(){
            $id = 1;
           // $user_id = $_SESSION['user_id'];
            $user = $this->Phreezer->Get('User', $id);
            print_r($_SESSION);
            echo $user->Name;
        }
        public function ViewTest(){
             $id = 1;
           // $user_id = $_SESSION['user_id'];
            $user = $this->Phreezer->Get('User', $id);
            $this->Render();
        }

	/**
	 * API Method queries for User records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new UserCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
                        //colocar um filtro aqui... se o usuario em questão não é admim bota um filtro e restringe a view de usuarios
                        if($_SESSION['permissao'] == 4){
                            $filter = $_SESSION['login'];
                        }
                        
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,Name,Username,Email,Password,Created,Modified,RoleId'
				, '%'.$filter.'%')
			);

			// TODO: this is generic query filtering based only on criteria properties
			foreach (array_keys($_REQUEST) as $prop)
			{
				$prop_normal = ucfirst($prop);
				$prop_equals = $prop_normal.'_Equals';

				if (property_exists($criteria, $prop_normal))
				{
					$criteria->$prop_normal = RequestUtil::Get($prop);
				}
				elseif (property_exists($criteria, $prop_equals))
				{
					// this is a convenience so that the _Equals suffix is not needed
					$criteria->$prop_equals = RequestUtil::Get($prop);
				}
			}

			$output = new stdClass();

			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$users = $this->Phreezer->Query('UserReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $users->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $users->TotalResults;
				$output->totalPages = $users->TotalPages;
				$output->pageSize = $users->PageSize;
				$output->currentPage = $users->CurrentPage;
			}
			else
			{
				// return all results
				$users = $this->Phreezer->Query('UserReporter',$criteria);
				$output->rows = $users->ToObjectArray(true, $this->SimpleObjectParams());
				$output->totalResults = count($output->rows);
				$output->totalPages = 1;
				$output->pageSize = $output->totalResults;
				$output->currentPage = 1;
			}


			$this->RenderJSON($output, $this->JSONPCallback());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method retrieves a single User record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$user = $this->Phreezer->Get('User',$pk);
			$this->RenderJSON($user, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new User record and render response as JSON
	 */
	public function Create()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$user = new User($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $user->Id = $this->SafeGetVal($json, 'id');
                     
			$user->Name = $this->SafeGetVal($json, 'name');
			$user->Username = $this->SafeGetVal($json, 'username');
			$user->Email = $this->SafeGetVal($json, 'email');
			$user->Password = md5($this->SafeGetVal($json, 'password'));
			$user->Created = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'created')));
			$user->Modified = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'modified')));
			$user->RoleId = $this->SafeGetVal($json, 'roleId');

			$user->Validate();
			$errors = $user->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$user->Save();
				$this->RenderJSON($user, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing User record and render response as JSON
	 */
	public function Update()
	{
		try
		{
						
			$json = json_decode(RequestUtil::GetBody());

			if (!$json)
			{
				throw new Exception('The request body does not contain valid JSON');
			}

			$pk = $this->GetRouter()->GetUrlParam('id');
			$user = $this->Phreezer->Get('User',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $user->Id = $this->SafeGetVal($json, 'id', $user->Id);
                        
                        
			$user->Name = $this->SafeGetVal($json, 'name', $user->Name);
			$user->Username = $this->SafeGetVal($json, 'username', $user->Username);
			$user->Email = $this->SafeGetVal($json, 'email', $user->Email);
			if($this->SafeGetVal($json, 'password') != $user->Password){
                            $user->Password = md5($this->SafeGetVal($json, 'password', $user->Password));
                        }
                        //$user->Password = md5($this->SafeGetVal($json, 'password', $user->Password));
			$user->Created = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'created', $user->Created)));
			$user->Modified = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'modified', $user->Modified)));
			$user->RoleId = $this->SafeGetVal($json, 'roleId', $user->RoleId);

			$user->Validate();
			$errors = $user->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$user->Save();
				$this->RenderJSON($user, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing User record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$user = $this->Phreezer->Get('User',$pk);

			$user->Delete();

			$output = new stdClass();

			$this->RenderJSON($output, $this->JSONPCallback());

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}
}

?>
