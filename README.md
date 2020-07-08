# SteamAuth
This library allows users to log in your site with their Steam account. On its own it won't do much, if you want to do something when an user has succesfully logged in then you need to add your own [anonymous function](https://www.php.net/manual/en/functions.anonymous.php).

## Why?
The goal of this library is to _only_ add the functionality to allow users with a Steam account to log into your website, allowing your own code.

## Example usage

```
$openid = new SteamAuth();
$openid->on_success = function ($steamid) {
  $_SESSION["steamid"] = $steamid;
};
$openid->initiate();
```

---

This constructs the class.

```
$openid = new SteamAuth();
```
We add an anonymous function to `on_success`, which fires when the user has logged in.

In our example function we are going to take the `$steamid` and save it in our current session.

```
$openid->on_success = function ($steamid) {
  $_SESSION["steamid"] = $steamid;
};
```

Here we take the user through the process.

```
$openid->initiate();
```

## Events

### on_success(string $steamid)

on_success is called when the user has logged in and will include the user's [steamID64](https://developer.valvesoftware.com/wiki/SteamID).

**By default it will do nothing**, a suggestion is to take the `$steamid` variable, save it in our current session and send the user to a dashboard.

```
$openid->on_success = function ($steamid) {
  $_SESSION["steamid"] = $steamid;
  return header("Location: /dashboard");
};
```

### on_cancel()

on_cancel is called when mode returned by OpenID equals `cancel`, this should typically never happen.

By default it will go back to the root page.

```
$openid->on_cancel = function () {
  return header("Location: /");
};
```

### on_exception(Exception $exception)

on_exception is called when (for some reason) an exception is thrown during the process.

By default it will print the exception's message and exit.

```
$this->on_exception = function (\Exception $e) {
  print $e->getMessage();
  exit;
};
```

## Pull Requests
As long as it does not defeat the purpose of this library, always welcoming PRs.

## Thanks
* iignatov and the contributors of [LightOpenID](https://github.com/iignatov/LightOpenID), it uses said library (with a few edits) to handle the OpenID protocol.

## License
https://opensource.org/licenses/mit-license.php
