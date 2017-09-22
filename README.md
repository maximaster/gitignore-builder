# GitIgnore Builder

Утилита, позволяющая создавать шаблоны генерации .gitignore.

В шаблонах можно импортировать внешние `.gitignore`-файлы, с помощью такой конструкции:
```
##import {"file": "path/to/file", "prefix": "htdocs"}
```
В `file` указывается путь к импортируемому файлу, а в `prefix` опционально можно указать какой префикс получат все правила указанные "от корня".

Сокращённая форма записи:
```
##import path/to/file
```