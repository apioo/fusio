
Schema
======

By default you need to write standard TypeSchema files to provide a schema for
the request and response format of an endpoint. Besides this it is also possible
to create a PHP class with specific annotations which can be transformed into
JSON Schema by Fusio. This has the advantage that your action will receive this
specific class as request body. At the ``resources/schemas.yaml`` file you can
define a new schema using a simple PHP class name instead of a file:

.. code-block:: php

    MySchema: 'App\Schema\Todo'

The schema class can look like i.e.:

.. code-block:: php

    <?php 
    
    namespace App\Schema;
    
    class Todo
    {
        /**
         * @var integer
         */
        protected $id;
        /**
         * @var integer
         */
        protected $status;
        /**
         * @var string
         * @MaxLength(64)
         */
        protected $title;
        /**
         * @var string
         * @Key("insert_date")
         * @Format("date-time")
         */
        protected $insertDate;
        /**
         * @param int $id
         */
        public function setId(?int $id)
        {
            $this->id = $id;
        }
        /**
         * @return int
         */
        public function getId() : ?int
        {
            return $this->id;
        }
        /**
         * @param int $status
         */
        public function setStatus(?int $status)
        {
            $this->status = $status;
        }
        /**
         * @return int
         */
        public function getStatus() : ?int
        {
            return $this->status;
        }
        /**
         * @param string $title
         */
        public function setTitle(?string $title)
        {
            $this->title = $title;
        }
        /**
         * @return string
         */
        public function getTitle() : ?string
        {
            return $this->title;
        }
        /**
         * @param \DateTime $insertDate
         */
        public function setInsertDate(?\DateTime $insertDate)
        {
            $this->insertDate = $insertDate;
        }
        /**
         * @return \DateTime
         */
        public function getInsertDate() : ?\DateTime
        {
            return $this->insertDate;
        }
    }

If you run the ``deploy`` command Fusio will generate a TypeSchema based on
the provided annotations. Fusio uses the `PSX Schema`_ library, please take a
look at project for more information about available annotations.

If you use the example schema class as request schema you would receive a
``PassthruRecord`` at your action. Through the ``getPayload`` method you can get
the complete ``Todo`` instance containing the data of the request.

.. code-block:: php

    <?php
    
    namespace App\Action;
    
    use Fusio\Engine\ActionAbstract;
    use Fusio\Engine\ContextInterface;
    use Fusio\Engine\ParametersInterface;
    use Fusio\Engine\RequestInterface;
    
    class Test extends ActionAbstract
    {
        public function handle(RequestInterface $request, ParametersInterface $configuration, ContextInterface $context)
        {
            /** @var \Fusio\Impl\Record\PassthruRecord $data */
            $data = $request->getBody();
    
            // access the getter of the todo instance through the symfony property access component
            $title = $data->getProperty('title');
    
            // get the App\Schema\Todo instance
            $todo = $data->getPayload();
    
            return $this->response->build(200, [], [
                'success' => true,
                'title' => $title
            ]);
        }
    }

.. _PSX Schema: https://github.com/apioo/psx-schema
