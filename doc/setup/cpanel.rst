
cPanel
======

On cPanel you can create a new sub-domain and use the "base document path"
option to point it at ``/public``. Also you need to set the ``psx_dispatch``
key at the ``configuration.php`` to ``''`` and adjust the url at the ``.env``
file to your correct domain.

