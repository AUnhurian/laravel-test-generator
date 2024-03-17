
# Laravel Test Generator
Use this package to generate test files for your Laravel application, and fix the tests later.

## Pay Attention
This package is still in development and may not work as expected. Use it at your own risk.

The results of the generated tests are not full. You should fill in this test your logic.
## Installation
Install the package using composer.

```bash
composer require aunhurian/laravel-test-generator
```

## Usage
```bash
php artisan test:generate "\App\Http\Controllers\IndexController" --feature --unit
```

You can use the `override` option to overwrite existing test files.

```bash
php artisan test:generate "\App\Http\Controllers\IndexController" --feature --unit --override
```

## Example
You have a controller `IndexController` with the following methods:

```php
class IndexController extends Controller
{
    public function __construct(private string $test = '')
    {
    }

    public function test(Request $request, int $id)
    {
        return view('welcome', [
            'data' => $request->all()
        ]);
    }

    public function show(): JsonResponse
    {
        return response()->json([
            'message' => 'success'
        ]);
    }
}
```

You can generate test files for the controller using the following command:

```bash
php artisan test:generate "\App\Http\Controllers\IndexController" --feature --unit
```

This will generate the following test files:

### Feature Test
It will generate a feature test file for the controller.
```php
<?php

namespace Tests\Feature\Http\Controllers;

use Symfony\Component\HttpFoundation\Response as SymphonyResponse;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testTest(): void
    {
        $url = route('test');
        
        $this->get($url)
            ->assertStatus(SymphonyResponse::HTTP_OK);
        
        $this->assertTrue(true);
    }

    public function testShow(): void
    {
        $data = [];
        
        $url = route('testing.test.show', [ 'id' => '',]);
        
        $this->postJson($url, $data)
            ->assertStatus(SymphonyResponse::HTTP_OK)
            ->assertJson([
                //TODO: Add your expected response here
            ]);
        
        $this->assertTrue(true);
    }

}
```

### Unit Test
It will also generate a unit test file for the controller.
```php
<?php

namespace Tests\Unit\Http\Controllers;

use App\Http\Controllers\IndexController;
use Illuminate\Http\Request;
use Tests\TestCase;

class IndexControllerTest extends TestCase
{
    public function testTest(): void
    {
        $test = '';
        $request = $this->mock(Request::class);
        $id = '';
        $IndexController = new IndexController($test);
        
        $IndexController->test($request, $id);
        
        $this->assertTrue(true);
    }

    public function testShow(): void
    {
        $test = '';
        $IndexController = new IndexController($test);
        
        $response = $IndexController->show();
        $this->assertNotEmpty($response);
        
        $this->assertTrue(true);
    }

}
```
