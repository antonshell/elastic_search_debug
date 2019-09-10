FROM elasticsearch:5.6.5

RUN apt-get update
RUN apt-get install -y mc

ADD ./elasticsearch.yml /usr/share/elasticsearch/config/
ADD ./log4j2.properties /usr/share/elasticsearch/config

RUN elasticsearch-plugin install http://dl.bintray.com/content/imotov/elasticsearch-plugins/org/elasticsearch/elasticsearch-analysis-morphology/5.6.5/elasticsearch-analysis-morphology-5.6.5.zip
RUN elasticsearch-plugin install -b ingest-attachment