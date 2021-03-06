# The Zend path

If you use the Zend Framework you can use this section
for bootstraping the library and other things.

## Load Resources

In your ```application.ini``` you have to load the UpCloo
resources namespace and the UpCloo general namespace as follow

```ini
autoloadernamespaces.UpCloo = "UpCloo_"
```

After that you have to load the resource section

```ini
;Consider the real path where you put UpCloo library
pluginPaths.UpCloo_Zend_Application_Resource = APPLICATION_PATH "/library/UpCloo/Zend/Application/Resource"
```

Now you are ready for load it as follow:

```ini
resources.upcloo.username = "your-username"
resources.upcloo.password = "your-password"
resources.upcloo.sitekey = "your-sitekey"
```

## Bootstrap with local storage

You can start the library using the local storage

```ini
resources.upcloo.storage = APPLICATION_PATH "/../system/storage.db"
```

UpCloo library starts using the local storage.

## Bootstrap resource in test (read-only)

If you want a read-only UpCloo instance for your testing scope you can use the
```UpClooMock``` client.

```ini
[testing : production]
resources.upcloo.client = "UpClooMock"

[development : production]
resources.upcloo.client = "UpClooMock"
```