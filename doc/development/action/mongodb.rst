
MongoDB
=======

The MongoDB connection is not available in the standard installation since it
requires the `ext-mongodb`_ PHP extension. Therefor you need to install the 
extension and regsiter the Fusio MongoDB adapter.

.. code-block:: text

    composer require fusio/adapter-mongodb
    php bin/fusio system:register "Fusio\Adapter\Mongodb\Adapter"

.. toctree::
   :maxdepth: 1

   mongodb/find
   mongodb/find-one
   mongodb/insert
   mongodb/update
   mongodb/delete


.. _ext-mongodb: https://github.com/mongodb/mongo-php-library
