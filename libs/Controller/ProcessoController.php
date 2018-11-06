<?php
/** @package    Gerenciamentos de Processos::Controller */

/** import supporting libraries */
require_once("AppBaseController.php");
require_once("Model/Processo.php");

/**
 * ProcessoController is the controller class for the Processo object.  The
 * controller is responsible for processing input from the user, reading/updating
 * the model as necessary and displaying the appropriate view.
 *
 * @package Gerenciamentos de Processos::Controller
 * @author ClassBuilder
 * @version 1.0
 */
class ProcessoController extends AppBaseController
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
	 * Displays a list view of Processo objects
	 */
	public function ListView()
	{
            // TODO: if authentiation is required for this entire controller, for example:
            $this->RequirePermission(ExampleUser::$PERMISSION_USER,'SecureExample.LoginForm','Por favor, realize autenticação de usuário');
            $this->Render();
	}

	/**
	 * API Method queries for Processo records and render as JSON
	 */
	public function Query()
	{
		try
		{
			$criteria = new ProcessoCriteria();
			
			// TODO: this will limit results based on all properties included in the filter list 
			$filter = RequestUtil::Get('filter');
			if ($filter) $criteria->AddFilter(
				new CriteriaFilter('Id,DataEntrada,Numero,Nome,Assunto,DataSaida,Observacao,SetorId'
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
                        //definindo uma ordem por data de entrada e data de saída
                        $criteria->SetOrder('DataEntrada',true);
                        $criteria->SetOrder('DataSaida',true);
			// if a sort order was specified then specify in the criteria
 			$output->orderBy = RequestUtil::Get('orderBy');
 			$output->orderDesc = RequestUtil::Get('orderDesc') != '';
 			if ($output->orderBy) $criteria->SetOrder($output->orderBy, $output->orderDesc);

			$page = RequestUtil::Get('page');

			if ($page != '')
			{
				// if page is specified, use this instead (at the expense of one extra count query)
				$pagesize = $this->GetDefaultPageSize();

				$processos = $this->Phreezer->Query('ProcessoReporter',$criteria)->GetDataPage($page, $pagesize);
				$output->rows = $processos->ToObjectArray(true,$this->SimpleObjectParams());
				$output->totalResults = $processos->TotalResults;
				$output->totalPages = $processos->TotalPages;
				$output->pageSize = $processos->PageSize;
				$output->currentPage = $processos->CurrentPage;
			}
			else
			{
				// return all results
				$processos = $this->Phreezer->Query('ProcessoReporter',$criteria);
				$output->rows = $processos->ToObjectArray(true, $this->SimpleObjectParams());
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
	 * API Method retrieves a single Processo record and render as JSON
	 */
	public function Read()
	{
		try
		{
			$pk = $this->GetRouter()->GetUrlParam('id');
			$processo = $this->Phreezer->Get('Processo',$pk);
			$this->RenderJSON($processo, $this->JSONPCallback(), true, $this->SimpleObjectParams());
		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method inserts a new Processo record and render response as JSON
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

			$processo = new Processo($this->Phreezer);

			// TODO: any fields that should not be inserted by the user should be commented out

			// this is an auto-increment.  uncomment if updating is allowed
			// $processo->Id = $this->SafeGetVal($json, 'id');

			$processo->DataEntrada = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEntrada')));
			$processo->Numero = $this->SafeGetVal($json, 'numero');
			$processo->Nome = $this->SafeGetVal($json, 'nome');
			$processo->Assunto = $this->SafeGetVal($json, 'assunto');
			$processo->DataSaida = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataSaida')));
                        $processo->Observacao = $this->SafeGetVal($json, 'observacao');
			$processo->SetorId = $this->SafeGetVal($json, 'setorId');

			$processo->Validate();
			$errors = $processo->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$processo->Save();
				$this->RenderJSON($processo, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}

		}
		catch (Exception $ex)
		{
			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method updates an existing Processo record and render response as JSON
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
			$processo = $this->Phreezer->Get('Processo',$pk);

			// TODO: any fields that should not be updated by the user should be commented out

			// this is a primary key.  uncomment if updating is allowed
			// $processo->Id = $this->SafeGetVal($json, 'id', $processo->Id);

			$processo->DataEntrada = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataEntrada', $processo->DataEntrada)));
			$processo->Numero = $this->SafeGetVal($json, 'numero', $processo->Numero);
			$processo->Nome = $this->SafeGetVal($json, 'nome', $processo->Nome);
			$processo->Assunto = $this->SafeGetVal($json, 'assunto', $processo->Assunto);
			$processo->DataSaida = date('Y-m-d H:i:s',strtotime($this->SafeGetVal($json, 'dataSaida', $processo->DataSaida)));
                        $processo->Observacao = $this->SafeGetVal($json, 'observacao', $processo->Observacao);
			$processo->SetorId = $this->SafeGetVal($json, 'setorId', $processo->SetorId);

			$processo->Validate();
			$errors = $processo->GetValidationErrors();

			if (count($errors) > 0)
			{
				$this->RenderErrorJSON('Please check the form for errors',$errors);
			}
			else
			{
				$processo->Save();
				$this->RenderJSON($processo, $this->JSONPCallback(), true, $this->SimpleObjectParams());
			}


		}
		catch (Exception $ex)
		{


			$this->RenderExceptionJSON($ex);
		}
	}

	/**
	 * API Method deletes an existing Processo record and render response as JSON
	 */
	public function Delete()
	{
		try
		{
						
			// TODO: if a soft delete is prefered, change this to update the deleted flag instead of hard-deleting

			$pk = $this->GetRouter()->GetUrlParam('id');
			$processo = $this->Phreezer->Get('Processo',$pk);

			$processo->Delete();

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
