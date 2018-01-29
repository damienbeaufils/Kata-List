# Archi Clean : Python

## Requirements
Python 3.x is required.
Please setup your virtualenv with Python 3 and install the requirements using the ```requirements.txt``` file provided.

```
pip install -r requirements.txt
```

## Unit test execution

```
pytest -v
```

## Code Coverage

```
pytest --cov=app --cov-report=html tests -v
```

Coverage summary is found in `htmlcov/index.html`.
