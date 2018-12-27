# Elastic search debug

Tool for debugging elastic search relevance.

# Install

1 . Clone repository
 
```
git clone https://github.com/antonshell/elastic_search_debug.git
```

2 . Copy & edit config. Set connection to elastic, edit search queries

```
cp _config.php.dist _config.php
nano _config.php
```

# Usage

1 . Test all queries:

```
php search_debug.php
```

Sample output:

```
Query: Пила
	Пила RACO - 227216 - Ножовки ручные
	Пила RACO - 227218 - Ножовки ручные
	Пила GRINDA - 227535 - Ножовки ручные
	...
Query: Ножовка
	Ножовка ZIPOWER PM4208 - 251204 - Ножовки ручные
	Ножовка SATA 93404 - 522876 - Ножовки ручные
	Ножовка JETTOOLS LBJ01A - 123412 - Ножовки ручные
	...
```