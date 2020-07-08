<?php

namespace sascheh\SteamAuth;

class SteamAuth
{
	/**
	 * When the user has succesfully logged in.
	 * 
	 * @param string $steamid steamid64
	 */
	public $on_success;

	/**
	 * When the OpenID protocol sends a 'cancel', sends them to the base URL of your website by default.
	 */
	public $on_cancel;

	/**
	 * When an exception is thrown during the process, sends them to the base URL of your website by default.
	 * 
	 * @param \Exception $e
	 */
	public $on_exception;

	function __construct()
	{
		$this->on_success = function (string $steamid) {
			$steamid = $steamid; // Prevent "Symbol is declared, but not used"
		};

		$this->on_cancel = function () {
			return header("Location: /");
		};

		$this->on_exception = function (\Exception $e) {
			$e = $e; // Prevent "Symbol is declared, but not used"
			print $e->getMessage();
			exit;
		};
	}

	/**
	 * Starts connecting to Steam using the OpenID protocol.
	 * 
	 * @return void
	 */
	public function initiate()
	{
		ob_start();
		try {
			$openid = new LightOpenID($_SERVER['HTTP_HOST']);

			// Visit the actual url
			if (!$openid->mode) {
				$openid->identity = 'https://steamcommunity.com/openid';
				return header("Location: {$openid->authUrl()}");
			}

			// Cancel has been passed, return 
			elseif ($openid->mode == 'cancel') {
				return ($this->on_cancel);
			}
			
			elseif ($openid->validate()) {
				// Retrieve Steam ID
				$id = $openid->identity;
				$regex = "/^https:\/\/steamcommunity\.com\/openid\/id\/(7[0-9]{15,25}+)$/";
				preg_match($regex, $id, $matches);

				return ($this->on_success)($matches[1]);
			}
		} catch (\Exception $e) {
			($this->on_exception)($e);
		}
	}
}
