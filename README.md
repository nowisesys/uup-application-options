UUP-APPLICATION-OPTIONS
=======================================================================

Supports transparent/uniform handling of runtime options from CLI command and HTTP request. 

This package supports short/long options, other options and reading password from terminal (masked echo output). For
HTTP request options, an optional filter can be applied.

### USAGE:

In the command action class, define a method that checks whether to return command line options (CLI) or from request
option (HTTP).

```php
private function getApplicationOptions(): ApplicationOptionsInterface
{
    if (php_sapi_name() == 'cli') {
        return new CommandLineOptions();
    } else {
        return new HttpRequestOptions();
    }
}
```

From within the application, a number of convenient methods can be used for checking if options was passed and retrieve
them type safe.

```php
public function setup() 
{
    $this->options = $this->getApplicationOptions();
}

public function execute(): void 
{
    // 
    // Example of setting defaults:
    // 
    if ($this->options->isMissing('user')) {
        $this->options->setOption('user', 'adam');
    }
    
    // 
    // Take action if option is defined:
    // 
    if ($this->options->hasOption('email')) {
        $this->sendEmail($this->options->getString('email'));
    }
}
```

A number of other getters exist, for example for boolean, float and integer values. These takes a second default value
that is returned of option is missing.

### OPTION ALIAS:

For command line options, the default behavior is to return options by stripping leading dashes ('-'). To support short
command options mapped to long options, pass a mapping array.

```php
private function getApplicationOptions(): ApplicationOptionsInterface
{
    if (php_sapi_name() == 'cli') {
        return new CommandLineOptions([
            '-f' => 'file',
            '-U' => 'user'
        ]);
    } else {
        return new HttpRequestOptions();
    }
}
```

These two short option will now be an alias for their equivalent long option. Some builtin aliases are implicit 
handled for command line options. These short options are:

* -h => help
* -V => version
* -d => debug
* -v => verbose
* -q => quiet

These are processed before user defined mappings, making it possible to easily redefine the builtin mapping.

### OPTION VALUES:

Option values are any value after the equal character ('='). For options without a value, the option key will be read
having boolean true as its value.

### DASHES:

Processing of command line options (CLI) will strip leading dashes and use the remaining string as the command option
key.

### OPTION FILTERING:

Command line options are considered safe. For HTTP request, it's possible to pass an array of sanitation filter to be
applied.

```php
private function getApplicationOptions(): ApplicationOptionsInterface
{
    if (php_sapi_name() == 'cli') {
        return new CommandLineOptions();
    } else {
        return new HttpRequestOptions(
            new HttpRequestFilter([
                'user'  => FILTER_SANITIZE_STRING,
                'email' => FILTER_SANITIZE_EMAIL
            ])
        );
    }
}
```

The default behavior is to not filter HTTP request options. For larger applications, some framework for purify HTML
input might be used that could be wrapped in a class that implements the `FilterInterface` and used instead of passing
an instance of the `HttpRequestFilter` class.

