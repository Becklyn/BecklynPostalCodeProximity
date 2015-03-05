Becklyn Postal Code Proximity
=============================

If you have a pre-generated database with a mapping of postal codes to latitude & longitude, this library will help you with searching for nearby postal codes (in a given radius).


## Usage
First you need to create a query generator. The query generator is responsible for generating the SQL for loading the data from the database.
You need to specify the names of the table and the columns of your postal code table.

```php
use Becklyn\PostalCodeProximity\QueryGenerator;

$queryGenerator = new QueryGenerator($tableName, $latitudeColumn = "lat", $longitudeColumn = "lng", $postalCodeColumn = "zip");
```

Afterwards, you need to use a database adapter. There are already working adapters for PDO and mysqli.

```php
use Becklyn\PostalCodeProximity\Adapter\PdoAdapter;

$pdo = new \PDO("...", "user", "password");
$databaseAdapter = new PdoAdapter($pdo, $queryGenerator);
```

or

```php
use Becklyn\PostalCodeProximity\Adapter\MysqliAdapter;

$mysqli = new \mysqli("localhost", "user", "password", "dbname");
$databaseAdapter = new MysqliAdapter($mysqli, $queryGenerator);
```

And now you can query for nearby postal codes:

```php
use Becklyn\PostalCodeProximity\PostalCodeLocator;

$postalCodeLocator = new PostalCodeLocator($databaseAdapter);
$postalCodeLocator->loadNearPostalCodes($postalCode, $radius, $limit = null);
```
*   `$postalCode` is the postal code, like `"12345"`
*   `$radius` is the radius in km (air-line distance)
*   `$limit` (optional) limit the number of results


### Return value
You will receive an array of `NearPostalCode`

```php
$nearPostalCode->getPostalCode();
$nearPostalCode->getDistanceInKm();
```


### Error cases
There are two possible error case (except of exceptions directly from the databases):

*   `AmbiguousPostalCodeException`: if a given postal code is found multiple times in the postal code database
*   `QueryException`: handles all error cases originating in the database. **Warning:** the exception messages may contain sensitive information.


## Recommended database structure

*   `postal_code CHAR(5)` (may vary depending on your country of origin)
*   `latitude DOUBLE`
*   `longitude DOUBLE`

You should add an unique index to the `postal_code` fields and indexes on `latitude` and `longitude`.
