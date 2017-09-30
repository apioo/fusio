
Adapter
=======

An adapter is a composer package which provides classes to extend the 
functionality of Fusio. Through an adapter it is i.e. possible to provide 
custom action/connection classes or to install predefined routes for an existing
system. Our `website`_ lists every available composer package which has the 
``fusio-adapter`` keyword defined in the ``composer.json`` file.

The adapter needs to require the ``fusio/engine`` package and must have an 
adapter class which implements the ``Fusio\Engine\AdapterInterface`` interface. 
This interface has a method ``getDefinition`` which returns an absolute path to 
a ``adapter.json`` definition file. This definition contains all information for 
Fusio how to extend the system. The adapter can be installed through the 
register command:

.. code-block:: text

    php bin/fusio system:register "Acme\System\Adapter"

In the following an example adapter definition which showcases all available 
parameters. There is also a complete `JsonSchema`_ which describes this format.

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
                        "request": "Adapter-Schema",
                        "responses": {
                            "200": "Passthru"
                        },
                        "action": "Void-Action"
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


.. _JsonSchema: https://github.com/apioo/fusio-engine/blob/master/src/Test/definition_schema.json
.. _website: https://www.fusio-project.org/adapter
