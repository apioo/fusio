
Adapter
=======

An adapter is a composer package which provides classes to extend the 
functionality of Fusio. Through an adapter it is i.e. possible to provide 
custom action/connection classes or to install predefined routes for an existing
system. A package needs to require the ``fusio/engine`` package and must have an 
adapter class which implements the ``Fusio\Engine\AdapterInterface`` class. This 
class has a method ``getDefinition`` which returns an absolute path to a 
``adapter.json`` definition. This definition contains all informations for Fusio 
how to extend the system. The adapter can be installed through the register 
command:

.. code-block:: text

    php bin/fusio register Acme\System\Adapter

In the following an example adater definition which showcases all available 
parameters

.. code-block:: json

    {
        "actionClass": ["Fusio\\Impl\\Adapter\\Test\\VoidAction"],
        "connectionClass": ["Fusio\\Impl\\Adapter\\Test\\VoidConnection"],
        "routes": [{
            "methods": "GET|POST|PUT|DELETE",
            "path": "/void",
            "config":[{
                "active": true,
                "status": 4,
                "name": "1",
                "methods": [{
                    "active": true,
                    "public": true,
                    "name": "GET",
                    "action": "${action.Void-Action}",
                    "request": "${schema.Adapter-Schema}",
                    "response": "${schema.Passthru}"
                }]
            }]
        }],
        "action": [{
            "name": "Void-Action",
            "class": "Fusio\\Impl\\Adapter\\Test\\VoidAction",
            "config": {
                "foo": "bar",
                "connection": "${connection.Adapter-Connection}"
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
            "class": "Fusio\\Impl\\Adapter\\Test\\VoidConnection",
            "config": {
                "foo": "bar"
            }
        }]
    }
