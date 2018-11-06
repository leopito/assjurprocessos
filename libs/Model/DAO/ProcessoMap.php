<?php
/** @package    Processos::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * ProcessoMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the ProcessoDAO to the processo datastore.
 *
 * WARNING: THIS IS AN AUTO-GENERATED FILE
 *
 * This file should generally not be edited by hand except in special circumstances.
 * You can override the default fetching strategies for KeyMaps in _config.php.
 * Leaving this file alone will allow easy re-generation of all DAOs in the event of schema changes
 *
 * @package Processos::Model::DAO
 * @author ClassBuilder
 * @version 1.0
 */
class ProcessoMap implements IDaoMap, IDaoMap2
{

	private static $KM;
	private static $FM;
	
	/**
	 * {@inheritdoc}
	 */
	public static function AddMap($property,FieldMap $map)
	{
		self::GetFieldMaps();
		self::$FM[$property] = $map;
	}
	
	/**
	 * {@inheritdoc}
	 */
	public static function SetFetchingStrategy($property,$loadType)
	{
		self::GetKeyMaps();
		self::$KM[$property]->LoadType = $loadType;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetFieldMaps()
	{
		if (self::$FM == null)
		{
			self::$FM = Array();
			self::$FM["Id"] = new FieldMap("Id","processo","id",true,FM_TYPE_INT,11,null,true);
			self::$FM["DataEntrada"] = new FieldMap("DataEntrada","processo","data_entrada",false,FM_TYPE_DATE,null,null,false);
			self::$FM["Numero"] = new FieldMap("Numero","processo","numero",false,FM_TYPE_VARCHAR,100,null,false);
			self::$FM["Nome"] = new FieldMap("Nome","processo","nome",false,FM_TYPE_VARCHAR,100,null,false);
			self::$FM["Assunto"] = new FieldMap("Assunto","processo","assunto",false,FM_TYPE_VARCHAR,100,null,false);
			self::$FM["DataSaida"] = new FieldMap("DataSaida","processo","data_saida",false,FM_TYPE_DATE,null,null,false);
			self::$FM["Observacao"] = new FieldMap("Observacao","processo","observacao",false,FM_TYPE_VARCHAR,255,null,false);
			self::$FM["SetorId"] = new FieldMap("SetorId","processo","setor_id",false,FM_TYPE_INT,11,null,false);
		}
		return self::$FM;
	}

	/**
	 * {@inheritdoc}
	 */
	public static function GetKeyMaps()
	{
		if (self::$KM == null)
		{
			self::$KM = Array();
			self::$KM["fk_processo_setor"] = new KeyMap("fk_processo_setor", "SetorId", "Setor", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>