
CREATE TABLE acme_news (
  id CHAR(36) PRIMARY KEY,
  title CHAR(64),
  content TEXT,
  insertDate DATETIME
);

INSERT INTO acme_news (id, title, content, insertDate) VALUES 
("d7a074be-c7fa-4154-9400-0179d3aa56d9", "Lorem ipsum dolor sit amet", "Lorem ipsum dolor sit amet, consetetur sadipscing elitr", "2017-04-02 19:31:00"),
("ef7a4864-86c2-408a-907b-1ad562098113", "Lorem ipsum dolor sit amet", "Lorem ipsum dolor sit amet, consetetur sadipscing elitr", "2017-04-02 19:32:00"),
("bc14708e-e450-42fe-97ca-7af793c47446", "Lorem ipsum dolor sit amet", "Lorem ipsum dolor sit amet, consetetur sadipscing elitr", "2017-04-02 19:33:00");
