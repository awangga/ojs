<?php

/**
 * @file classes/plugins/AuthPlugin.inc.php
 *
 * Copyright (c) 2014-2015 Simon Fraser University Library
 * Copyright (c) 2003-2015 John Willinsky
 * Distributed under the GNU GPL v2. For full terms see the file docs/COPYING.
 *
 * @class AuthPlugin
 * @ingroup plugins
 *
 * @brief Abstract class for authentication plugins.
 *
 * TODO: Error reporting when updating remote source fails.
 * TODO: Support importing user accounts from the authentication source into OJS.
 */

define('AUTH_PLUGIN_CATEGORY', 'auth');
import('lib.pkp.classes.plugins.Plugin');

abstract class AuthPlugin extends Plugin {

	/** @var array settings for this plugin instance */
	var $settings;

	/** @var int auth source ID for this plugin instance */
	var $authId;

	/**
	 * Constructor.
	 * @param $settings array
	 * @param $authId int ID for this instance
	 */
	function AuthPlugin($settings = array(), $authId = null) {
		parent::Plugin();
		$this->settings = $settings;
		$this->authId = $authId;
	}


	//
	// General Plugin Functions
	//
	/**
	 * Return the path to a template for plugin settings.
	 * Can return null if there are no plugin-specific settings.
	 * @return string
	 */
	function getSettingsTemplate() {
		return $this->getTemplatePath() . 'settings.tpl';
	}


	//
	// Wrapper Functions
	//
	/**
	 * Update local user profile from the remote source, if enabled.
	 * @param $user User
	 * @return boolean true if successful
	 */
	function doGetUserInfo($user) {
		if (isset($this->settings['syncProfiles'])) {
			return $this->getUserInfo($user);
		}
		return false;
	}

	/**
	 * Update remote user profile, if enabled.
	 * @param $user User
	 * @return boolean true if successful
	 */
	function doSetUserInfo($user) {
		if (isset($this->settings['syncProfiles'])) {
			return $this->setUserInfo($user);
		}
		return false;
	}

	/**
	 * Update remote user password, if enabled.
	 * @param $username string
	 * @param $password string
	 * @return boolean true if successful
	 */
	function doSetUserPassword($username, $password) {
		if (isset($this->settings['syncPasswords'])) {
			return $this->setUserPassword($username, $password);
		}
		return false;
	}

	/**
	 * Create remote user account, if enabled.
	 * @param $user User to create
	 * @return boolean true if successful
	 */
	function doCreateUser($user) {
		if (isset($this->settings['createUsers'])) {
			return $this->createUser($user);
		}
		return false;
	}


	//
	// Core Plugin Functions
	// (Must be implemented by every authentication plugin)
	//
	/**
	 * Returns an instance of the authentication plugin
	 * @param $settings array settings specific to this instance
	 * @param $authId int identifier for this instance
	 * @return AuthPlugin
	 */
	abstract function getInstance($settings, $authId);

	/**
	 * Authenticate a username and password.
	 * @param $username string
	 * @param $password string
	 * @return boolean true if authentication is successful
	 */
	abstract function authenticate($username, $password);


	//
	// Optional Plugin Functions
	// (Required for extended functionality but not for authentication-only plugins)
	//
	/**
	 * Check if a username exists.
	 * @param $username string
	 * @return boolean
	 */
	function userExists($username) {
		return false;
	}

	/**
	 * Retrieve user profile information from the remote source.
	 * Any unsupported fields (e.g., OJS-specific ones) should not be modified.
	 * @param $user User to update
	 * @return boolean true if successful
	 */
	function getUserInfo($user) {
		return false;
	}

	/**
	 * Store user profile information on the remote source.
	 * @param $user User to store
	 * @return boolean true if successful
	 */
	function setUserInfo($user) {
		return false;
	}

	/**
	 * Change a user's password on the remote source.
	 * @param $username string user to update
	 * @param $password string the new password
	 * @return boolean true if successful
	 */
	function setUserPassword($username, $password) {
		return false;
	}

	/**
	 * Create a user on the remote source.
	 * @param $user User to create
	 * @return boolean true if successful
	 */
	function createUser($user) {
		return false;
	}

	/**
	 * Delete a user from the remote source.
	 * This function is currently not used within OJS,
	 * but is reserved for future use.
	 * @param $username string user to delete
	 * @return boolean true if successful
	 */
	function deleteUser($username) {
		return false;
	}

	/**
	 * Return true iff this is a site-wide plugin.
	 */
	function isSitePlugin() {
		return true;
	}
}

?>
