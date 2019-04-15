# PHP Framework
This was built just for fun, to see how quickly a framework could
be built with a laravel-style MVC architecture. Apparently,
pretty darn quick!

I wouldn't recommend using this as it's not going to be supported
but it would give you a good insight as to how I architect a
PHP program.

To run this:

    use PHPCodex\Framework\App;

And then initialise the resource.

    App::Instance();
    
This will run the resource and identify whether you are running
from the CLI or Web interface. While this project is the
framework, you should have a wrapper around this
which only requires this project.