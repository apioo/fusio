
Docker
======

Alternatively it is also possible to setup a Fusio system through docker. This
has the advantage that you automatically get a complete running Fusio system
without configuration. This is especially great for testing and evaluation. To 
setup the container you have to checkout the `repository`_ and run the following 
command:

.. code-block:: text

    docker-compose up -d

This builds the Fusio system with a predefined backend account. The credentials 
are taken from the env variables ``FUSIO_BACKEND_USER``, ``FUSIO_BACKEND_EMAIL`` 
and ``FUSIO_BACKEND_PW`` in the `docker-compose.yml`_. If you are planing to run 
the container on the internet you MUST change these credentials.


.. _repository: https://github.com/apioo/fusio-docker
.. _docker-compose.yml: https://github.com/apioo/fusio-docker/blob/master/docker-compose.yml
