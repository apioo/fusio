
Adapter
=======

An adapter is a composer package which provides classes to extend the 
functionality of Fusio. Through an adapter it is i.e. possible to provide 
custom action/connection classes or to install predefined routes for an existing
system. A package needs to require the ``fusio/engine`` package and must have an 
adapter class which implements the ``Fusio\Engine\AdapterInterface`` class. This 
class has a method ``getDefinition`` which returns an absolute path to a 
``adapter.json`` definition. This definition contains all information for Fusio 
how to extend the system. The adapter can be installed through the register 
command:

.. code-block:: text

    php bin/fusio system:register Acme\System\Adapter

In the following an example adapter definition which showcases all available 
parameters.

.. code-block:: json
 
    {
        "actionClass": ["Fusio\\Impl\\Tests\\Adapter\\Test\\VoidAction"],
        "connectionClass": ["Fusio\\Impl\\Tests\\Adapter\\Test\\VoidConnection"],
        "routes": [{
            "path": "/void",
            "config": [{
                "version": 1,
                "status": 4,
                "methods": {
                    "GET": {
                        "active": true,
                        "public": true,
                        "action": "Void-Action",
                        "request": "Adapter-Schema",
                        "response": "Passthru"
                    }
                }
            }]
        }],
        "action": [{
            "name": "Void-Action",
            "class": "Fusio\\Impl\\Tests\\Adapter\\Test\\VoidAction",
            "config": {
                "foo": "bar",
                "connection": "Adapter-Connection"
            }
        }],
        "schema": [{
            "name": "Adapter-Schema",
            "source": {
                "id": "http://fusio-project.org",
                "title": "process",
                "type": "object",
                "properties": {
                    "logId": {
                        "type": "integer"
                    },
                    "title": {
                        "type": "string"
                    },
                    "content": {
                        "type": "string"
                    }
                }
            }
        }],
        "connection": [{
            "name": "Adapter-Connection",
            "class": "Fusio\\Impl\\Tests\\Adapter\\Test\\VoidConnection",
            "config": {
                "foo": "bar"
            }
        }]
    }

It is also possible to generate such a definition on an existing system through 
the ``system:export`` command.

.. code-block:: text

    php bin/fusio system:export > export.json
