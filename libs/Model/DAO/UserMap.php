<?php
/** @package    Processos::Model::DAO */

/** import supporting libraries */
require_once("verysimple/Phreeze/IDaoMap.php");
require_once("verysimple/Phreeze/IDaoMap2.php");

/**
 * UserMap is a static class with functions used to get FieldMap and KeyMap information that
 * is used by Phreeze to map the UserDAO to the user datastore.
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
class UserMap implements IDaoMap, IDaoMap2
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
			self::$FM["Id"] = new FieldMap("Id","user","id",true,FM_TYPE_INT,11,null,true);
			self::$FM["Name"] = new FieldMap("Name","user","name",false,FM_TYPE_VARCHAR,255,null,false);
			self::$FM["Username"] = new FieldMap("Username","user","username",false,FM_TYPE_VARCHAR,20,null,false);
			self::$FM["Email"] = new FieldMap("Email","user","email",false,FM_TYPE_VARCHAR,100,null,false);
			self::$FM["Password"] = new FieldMap("Password","user","password",false,FM_TYPE_VARCHAR,255,null,false);
			self::$FM["Created"] = new FieldMap("Created","user","created",false,FM_TYPE_DATETIME,null,null,false);
			self::$FM["Modified"] = new FieldMap("Modified","user","modified",false,FM_TYPE_DATETIME,null,null,false);
			self::$FM["RoleId"] = new FieldMap("RoleId","user","role_id",false,FM_TYPE_INT,11,null,false);
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
			self::$KM["fk_users_roles1"] = new KeyMap("fk_users_roles1", "RoleId", "Role", "Id", KM_TYPE_MANYTOONE, KM_LOAD_LAZY); // you change to KM_LOAD_EAGER here or (preferrably) make the change in _config.php
		}
		return self::$KM;
	}

}

?>