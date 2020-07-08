# SteamAuth
This library allows Steam users to log in your site and it is made to just do only that. You need to add your own [anonymous functions](https://www.php.net/manual/en/functions.anonymous.php).

## Why?
The goal of this library is to _only_ add the functionality to allow users with a Steam account to log into your website, you control the rest.

## Example usage

```
$openid = new Auth("key", '/fail', '/success');
$openid->on_success = function ($steamid) {
  $_SESSION["steamid"] = $steamid;
};
$openid->initiate();
```

---

This constructs the class.

```
$openid = new Auth("key", '/fail', '/success');
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

```
$openid->on_success = function ($steamid) {
  $_SESSION["steamid"] = $steamid;
};
```

### on_cancel()

on_cancel is called when mode returned by OpenID equals `cancel`, this should typically never happen.

```
$openid->on_cancel = function () {
  return header("Location: /cancelled");
};
```


### on_exception(Exception $eexception)

on_exception is called when (for some reason) an exception is thrown during the process.

```
$openid->on_exception = function ($exception) {
  print $exception->getMessage();
  exit;
};
```

## Pull Requests
As long as it does not defeat the purpose of this library, always welcoming PRs.

## Thanks
* iignatov and the contributors of [LightOpenID](https://github.com/iignatov/LightOpenID), it uses said library to handle the OpenID protocol.

## License
https://opensource.org/licenses/mit-license.php
