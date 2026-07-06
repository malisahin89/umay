<?php

declare(strict_types=1);

namespace Tests\Unit;

use Core\FormRequest;
use Core\Request;
use Tests\TestCase;

class TestFormRequest extends FormRequest
{
    public function rules(): array
    {
        return ['name' => 'required'];
    }
}

class RequestFixTest extends TestCase
{
    public function test_form_request_uses_parent_state()
    {
        // 1. Create state with Request setters
        // 1. Request setter'ları ile state oluştur
        $request = new Request(
            ['page' => '2'],                // GET
            ['name' => 'Ali'],              // POST
            [],                             // FILES
            ['REQUEST_METHOD' => 'POST'],   // SERVER
            ['session_id' => 'abc']         // COOKIES
        );

        $this->assertEquals(['page' => '2'], $request->getQuery());
        $this->assertEquals(['name' => 'Ali'], $request->getPost());
        $this->assertEquals(['session_id' => 'abc'], $request->getCookies());

        // 2. FormRequest createFrom()
        // 2. FormRequest createFrom()
        $formRequest = TestFormRequest::createFrom($request);

        // Does FormRequest have the same data?
        // FormRequest aynı verilere sahip mi?
        $this->assertEquals('Ali', $formRequest->input('name'));
        $this->assertEquals('2', $formRequest->input('page'));
        $this->assertEquals('POST', $formRequest->method());
    }

    public function test_json_body_parsing()
    {
        // Normally since it reads file_get_contents('php://input'), testing this directly
        // Normalde file_get_contents('php://input') okuduğu için bunu doğrudan test etmek
        // in unit tests is difficult (requires stream wrapper override).
        // unit testte zordur (stream wrapper override gerekir).
        // However, we can check if body parsing was done after passing the $_SERVER array
        // Ancak biz Request nesnesini construct ederken $_SERVER array'ini verip,
        // when constructing the Request object.
        // sonrasında body parse yapılıp yapılmadığına bakabiliriz.
        // Let's test based on how the constructor of the Core/Request class works.
        // Core/Request sınıfının constructor'ı nasıl çalışıyor ona göre test edelim.
        // If json parsing is done in the constructor, it cannot be tested without overriding php://input.
        // Eğer json parsing constructor'da yapılıyorsa php://input override edilmeden test edilemez.

        // Making this testable without using php://input is difficult,
        // Bu testi php://input kullanmadan test edilebilir hale getirmek zordur,
        // but a stream wrapper could be written on php://memory.
        // ancak php://memory üzerine stream wrapper yazılabilir.
        $this->markTestIncomplete('JSON body parsing testi php://input mocking gerektirir.');
    }
}
