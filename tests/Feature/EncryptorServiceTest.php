<?php

use App\Business\Services\EncryptorService;

test('Prueba de Encriptador', function () {
    $key ='anyKey';

    $encryptor = new EncryptorService($key);

    $originalString = 'Hello, World!';
    $encryptedString = $encryptor->encrypt($originalString);

    expect($encryptedString)->not()->toBe($originalString);

    $decryptedString = $encryptor->decrypt($encryptedString);

    expect($decryptedString)->toBe($originalString);
});

test('Prueba de Encriptador con clave incorrecta', function () {
    $key1 = 'anyKey';
    $key2 = 'wrongKey';

    $encryptor1 = new EncryptorService($key1);
    $encryptor2 = new EncryptorService($key2);

    $encryptedString = $encryptor1->encrypt('test string');
    $this ->expectException(Exception::class);
    $encryptor2->decrypt($encryptedString);
});
