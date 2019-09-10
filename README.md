# Elastic search debug

Tool for debugging elastic search relevance.

# Install

1 . Clone repository
 
```
git clone https://github.com/antonshell/elastic_search_debug.git
```

2 . Copy & edit config. Set connection to elastic, edit search queries

```
cp docker-compose.yml.dist docker-compose.yml
cp _config.php.dist _config.php
nano _config.php
```

3 . Start elastic 

```
docker-compose up
```

4 . Create indexes, import data to Elastic 

```
php push_data.php
```

# Usage

1 . Test all queries:

```
php search_debug.php
```

Sample output:

```
Query: Honda
	Honda Ridgeline 2006
	Honda Ridgeline 2010
	Honda Prelude 1986
	...
Query: Ford
	Ford F250 2006
	Ford Escort 2002
	Ford Fiesta 2012
	...
Query: Chrysler
	Chrysler Concorde 1997
	Chrysler 300M 1999
	Chrysler Imperial 1993
	...
```