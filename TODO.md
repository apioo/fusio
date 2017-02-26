_Ever growing list of ideas and thoughts how to improve and extend Fusio. Topics_ 
_which are likely to be implemented are moved to a separate Github issue_

- [ ] Add option to set the status of an action or connection to disabled so it 
      can not be changed
- [ ] Extend API controller test cases to 100%
- [ ] Checkout whether we can use the eclipse orion file editor 
      (https://orionhub.org) to create a action in js
- [ ] At the moment it is possible to register the same adapter multiple times.
      Is this ok or should we add a check which tracks installed adapters?
- [ ] Simplify creating JSONSchema schemas for the request/response
  - [ ] A option to create a JSONSchema from a database table
  - [ ] A option to create a JSONSchema from a JSON string (see http://jsonschema.net/#/)
- [ ] Add consumer and documentation protractor tests
- [ ] Maybe we need to handle the idle status in the backend through
      https://github.com/HackedByChinese/ng-idle
- [ ] Consumer if access was already granted dont ask the user again instead 
      redirect the user directly or show the access token. We could add this 
      logic maybe to the meta lookup response
- [ ] Test GitHub/Google/Facebook login
- [ ] General test. Build an API and use different libraries to test OAuth2 
      interoperability
- [ ] Add more SDKs which help connecting to a Fusio API
- [ ] Probably add link to the doc to get an access token to send authenticated
      calls to the api
- [ ] Consumer login beside GitHub, Facebook, etc. provide a way to support 
      other login providers i.e. (LDAP)
- [ ] System export fix sort so that i.e. schemas which refer no other schema
      are at the top 
- [ ] Add general API performance panel where we measure the execution time of 
      each endpoint
- [ ] Optimize the way how data is organized. I.e. we could have many actions or 
      schemas, we need a way to group or categorize entries so it is easier to
      organize
  - [x] Add filter action/schema by route and add link from routes to 
        action/schema with filtered route id
  - [ ] Probably use https://github.com/nickperkinslondon/angular-bootstrap-nav-tree
- [ ] Write backend in TypeScript JS6 support transition to Angular2
  - [x] We have transition to browserify so we could easily use the typescript
        processor
- [ ] Add system log where every login and system change is shown
  - [ ] Add login bruteforce protection. Therefor we must log the ip of every 
        login attempt
  - [ ] We should add a limitation to the access token endpoint this would
        protect also the login
- [ ] Unify backend filter usage
- [ ] Schema definitions limitations
  - [ ] There is no way to add query parameters
  - [ ] At the moment we only handle the schema for an 200 return. Probably add 
        possibility to add response schema for other status codes
- [ ] Build new adapters
  - [ ] Add OData connection
  - [ ] ~~Build adapters for popular systems i.e. wordpress/drupal etc.~~
  - [ ] Gateways to other systems which dont have an API
  - [ ] Gateways to complex systems i.e. sharepoint
- [ ] Consider disable request model for DELETE method since a DELETE should
      probably not include a request body?

### Meta

- [ ] Update Fusio tutorial videos to showcase different topics
  - [x] Create API from SQL table
  - [ ] Create API from complex SQL query
  - [ ] Insert entry into a SQL table
  - [x] Create API with MongoDB
  - [ ] Insert entry into a MongoDB
  - [ ] Push entry into a RabbitMQ
  - [ ] Push entry into Beanstalk
  - [ ] Javascript app request protected endpoints
  - [ ] Howto develop a custom action
  - [x] Howto setup Fusio
  - [ ] Howto cache response of a slow action
  - [ ] Create API based on API spec swagger/RAML
- [ ] Add training howto develop a javascript app based on Fusio
- [ ] Add use case section to website
- [ ] Add a training section to fusio-project.org similar to:
      https://developer.android.com/training/basics/firstapp/creating-project.html
- [ ] Extend manual
- [ ] Extend the documentation how to write an adapter
- [ ] For the stable release write about Fusio at 
  - [ ] http://nordicapis.com/create-with-us/
  - [ ] https://www.reddit.com/r/php / https://www.reddit.com/r/rest
  - [ ] http://www.sitepoint.com/

### Archive

- [x] We need to create a marketplace where all adapters are listed and where
      developers can provide a adapter for their system
  - [x] The best way todo this is to parse all packages with a specific tag 
        "fusio-adapter" from packagist add list them on fusio-project.org
        There is an API which we cna use to request specific tags
- [x] Add action which builds a REST API from a table where a user only needs 
      to provide the table name. The action needs two routes /table and 
      /table/:id
- [x] Add SOAP connection
- [ ] ~~Add an action merge which combines the result of two actions into one 
      result (This is probably already possible through the transform action)~~
- [x] Provide a video with a installation and demo about the system
- [x] We must host the demo system somewhere
- [x] Maybe create icon
- [x] Register domain maybe fusio-api.org or fusio-project.org
- [x] Add a online demo system
- [ ] ~~Add beta phase on the website. Where users can enter an email for an invite~~
- [x] Setup discourse forum (we have a google group)
- [x] Added Fusio to https://github.com/marmelab/awesome-rest
- [x] Move Fusio samples to apioo org
- [ ] ~~Add phpinfo action~~
- [x] A option to configure a memcache server in the config
      Is possible through the psx config
- [x] Add docker and vagrant box to easy test and deploy fusio. We have a docker
      image a vagrant box is not needed
- [x] When creating an connection verify that the connection works #2
      Add a PingableInterface to connection which support this and add check
      to the service
- [x] Error-Log UI where all errors are listed
- [x] Build elektron app (http://electron.atom.io/) around backend
- [x] Add support for multiple other database backends. We should probably move
      these connections into seperate adapters.
  - [ ] ~~CouchDB~~
  - [x] Neo4j
  - [x] Cassandra
  - [x] Elasticsearch
- [x] Test message queues and probably move them also to a seperate adapter
  - [x] RabbitMQ
  - [x] Beanstalkd
- [ ] ~~Improve cache action add option to specific get or uri parameters which
      come into the cache key~~ The cache action was removed
- [ ] ~~Add doc endpoints where a user can create tutorials which are listed on
      the developer page~~ This can be done by modifying the js app
- [x] If a connection is referenced in an adapter we probably need to provide a
      way to change this connection
- [x] Add action which can build a REST API for a table
- [x] Import allow swagger format (probably wait for swagger v3?)
- [ ] ~~Adapter if we reference a connection in the definition the connection may 
      not exist. The adapter definition should be able to reference the
      selected database connection (we select only a connection if the adapter 
      inserts a table) or the user must have a way to specify one~~
- [ ] ~~Template add method to access SQL vendor independent queries from a 
      template based on the selected connection. I.e. in a SQL template you 
      could write {{ sql.getNowExpression() }} to get for mysql i.e. NOW(). This
      allows adapter to write independent sql. But it is also a security risk
      if users pass in user input into the arguments~~
- [ ] ~~Database dont show update/delete button if no table is selected~~
- [ ] ~~Automatically add the consumer scope the to the scope list~~
- [x] Option to ratelimit specific apps. Option to set global rate limits per
      config? #19
- [ ] ~~Database add data tab to browse the data of an existing table #17~~
- [ ] ~~Add option to disable changing the database schema through a config flag #18~~
- [x] Add serverless action (https://azure.microsoft.com/en-us/services/functions/)
      where you can write code in different languages which gets executed
  - [x] Evaluate V8Js and check whether we could write an action where a user can 
        write an action in javascript
  - [ ] ~~Providing an action which executes PHP is possible but probably not an
        option since we have no solid PHP sandbox~~ (also it is simple possible
        to write a custom action)
- [ ] ~~Database endpoint should also handle NoSQL databases~~
- [ ] ~~Probably we need a new panel "Plugins" where a user can register new
      adapter through composer~~
- [x] Moved the backend app into the fusio/ folder thus we free up some paths 
      for the API to use
- [x] Remove template from engine
- [x] Remove all actions except for (V8Processor)
  - [x] Because of that we dont need the http and util adapter
  - [x] All other adapter provide only the connection
  - [x] The import and default values use the util response action this is not
        possible anymore (maybe we let the static response action but remove the 
        template part)
- [x] The customer has now two options to implement the backend logic either
  - [x] Use the v8 processor
  - [x] Or build a native php action
- [x] We remove the database endpoint and panel
- [x] Add Swagger import support
- [x] Backend database UI add auto increment option
- [x] Add failover action which executes another action in case of an error
- [x] Schema add preview button and close window always on save
- [x] Add option to test action to see the response format of the action. 
      Probably also a way to compare the response with a schema
- [x] Add option to store variables with an app which can be used for conditions 
      or in an action #6
- [x] Add proper CORS handling
- [x] Schema if a schema name changes or it gets deleted what happens with 
      schemas which refer to the changed schema? #3
  - [x] Since the cache includes the complete resolved schema the problem occurs
        of the user wants to save the schema which refers to an deleted schema
- [x] When creating an action verify that the action works. How can we handle 
      template parameters for testing? We can now test the action on update.
- [x] Add action to simplify creating more complex logic (if else, switch).
  - [ ] ~~ In long term we could build a flowchart like yahoo pipes to build the 
        actions~~ For complex cases a user wants to write code
  - [ ] ~~ Use http://js.cytoscape.org/ to build a graph from the actions/schema ~~
  - [x] Write a processor action where a user can define multiple actions in
        a YAML format
- [x] Update config help texts
- [x] Add CORS support to backend config
- [x] Installer handle upgrade from 0.2.x to 0.3.x
- [x] Complete Fusio consumer Account/ChangePassword API
- [x] Think about action to build better nested responses
  - [ ] ~~Option to execute another action inside a template~~
  - [x] In general we need some kind of data templating engine where it is easy
        to write json data and call other actions etc.
- [x] Add database protractor tests
- [x] Add transactions to services
- [x] Disable edit button of scope backend, consumer and authorization since a 
      user would remove all assigned routes on save #13
- [ ] ~~Provide actions to create an API from a file i.e. JSON, XML maybe CSV?~~
- [x] Option to manually generate an access token
  - [ ] ~~Option to generate an access token for an application through the 
        backend. Maybe with an option to specify the expire time~~
  - [x] Probably add also an command to generate an access token
- [x] Simplifiy creating schemas
  - [ ] ~~Probably add third party java tool where it is possible to model  
        easily an schema~~ (This should be in any case an external project)
  - [x] Add possibility to upload json schema or add import json schema command
  - [x] If an json schema has no id probably use the psx_url from the config
        At the moment an error occurs if no id is available. Actually we need 
        the id only if we reference other schemas to resolve the $ref path
- [x] We need an adapter system. An adapter provides all configurations which 
      you need to create a REST API from a 3rd party system. The adapter uses 
      the core actions or provides actions by itself. It can use composer to get 
      dependencies. Fusio provides a custom adapter installer which handles all
      the logic
  - [x] Through this is would be possible to create a API for an existing
        system with i.e. "composer require fusio/drupal-adapter"
  - [x] The installer must ask the user for i.e. the API base path or the 
        connection which sould be used
- [ ] We probably need an marketplace for actions where we can provide 
      connectors for different software
  - [ ] New actions can be easily installed through composer. They must be only
        added to the configuration.php
  - [x] Through this we could create APIs for many server applications
  - [x] It should be possible for an action to define the routes which are 
        required for an action i.e. we could build a mail action and this action
        would needs one POST endpoint (i.e. /send) where we send a mail on post. 
        So it is not required that the endpoint is named /send but we must give
        the user the option to install the route. Probably an action should be
        wrapped as phar which we only have to copy to a sepcific folder
- [ ] ~~Add action "exec" which executes given PHP code~~
  - [x] It is easy possible to provide a custom action
- [ ] ~~Add general config. Probably to set the expire time of the oauth token.~~
  - [x] At the moment we have all config entries in the configuration.php which
        is sufficient
- [x] Encrypt credentials in connection config field
- [x] Improve cache action, consider path parameters etc.
- [ ] ~~Add support for jsonapi format http://jsonapi.org/~~
      (We dont want to force a data format for the user. If a user wants to 
      generate such a response format it should be no problem to generate)
- [x] Add schema importer i.e. swagger, raml, etc. in order to easily generate
      the API endpoints. Probably start with swagger since its the most popular 
      (We have added a RAML importer)
  - [ ] ~~We could use http://ngmodules.org/modules/ng-file-upload for upload~~
- [x] Add mongodb action
- [x] At the moment the Oauth endpoint supports only "Resource Owner Password 
      Credentials" we need also support for "Authorization Code" so that the API
      can be used for web apps. Therefor we must provide a way for an consumer 
      to login and grant an app authorization / manage authorized apps etc. 
      Probably this can also be done through an API so that an API provider can 
      provide its own UI for the user #5
- [x] JSONSchema export API which replaces the schema:// references with an API
      endpoint url (we have now a json schema export command)
- [x] Add RAML import. We need probably a general import field where someone can
      upload a api sepc file (RAML) which gets then imported
- [x] Return only actions and schema which are active or locked. Disabled edit 
      and delete button for locked actions/schema
- [x] Add transform action to transform an incoming request into another form 
      i.e. with jsonpatch https://tools.ietf.org/html/rfc6902 #9
- [x] "Proxy" action which provides a way to call a different API and use the 
      response
  - [x] Probably we need different PROXY actions i.e. JSON, SOAP, XML etc.
  - [x] It must be possible to configure custom headers or at leat
        authentication
- [x] We need a overall function which normalizes the result of an action to a 
      consistent format
- [x] Add route select filter for action and schemas
- [x] Access token use better randoum source for generation
- [x] Add validation panel where a user can register a validator for a specific
      field inside a schema. The validator uses also the expression language. A
      adapter can also register new validators. 
- [x] It should be not possible to delete a route which has a API version in 
      production/deprecated status only in development and closed
- [x] If an routes was deleted the status flag is set to 0 .. it is then not
      possible to create an route with the same name
- [x] It should be not possible to delete an route which is in production status
- [x] If we change the API status from development to production we should copy
      all request/response/action data so that further edits dont change the API
      behaviour
- [x] On route save store all used actions and schemas in a seperate relation 
      table
- [x] If a route is moved from development to production mode lock all used 
      schemas and actions
- [x] Handle status for actions and schemas. Possible status: action, locked, 
      deleted. If a action or schema gets delete set only the status flag
- [x] Update /documentation html files (probably use AngularJS with material 
      design)
- [x] Add change password dialog for user account
- [x] Log errors from actions
- [x] Add statistic point where a user can get more detailed informations about 
      the apps/requests etc. A more detailed version of the dashboard where it 
      is possible to select a timeframe
- [x] Add better handling for passthru schema in documentation i.e. an box that 
      no schema was specified
- [x] Return all Apps per user on user details request
- [x] Add more hints to form inputs and link to more details help
- [x] Add install sample data command. The install command installs all needed 
      db entries so we dont need directly the sample data
- [x] Check delete dialogs
- [x] Documentation viewer add error handling if API returns 500
- [x] Add request rate limitations
      Is available through condition action an rateLimit methods
- [x] Scope tokens fix expires on text
- [x] Remove /doc routes from the scope
- [x] Dont allow /doc, /backend and /authentication as route path
- [x] Add useradd command
- [x] Add install script
- [x] Add action and backend api tests
- [x] Add authentication
- [x] Update create Route view
- [x] Schema add ace json editor where a user can insert the json schema. 
      References to other schemas are with "$ref": "#/definition/[schema_name]"
  - [x] Based on the schema we create an definition file which gets referenced 
        on the sql table
- [x] Possibility to set status level for the API. When the API is in production
      status it is not possible to change the models. Maybe we should copy the 
      used schema some where so that it dont changed.
  - [x] An API has the following status lifecycle: 
        development -> production -> deprecated
- [x] Add option to version an API. If you want change an production API you 
      have to add a new version
