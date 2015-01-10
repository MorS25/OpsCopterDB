<?php

namespace OpsCopter\DB\ProjectBundle\Tests\Validator\Constraints;

use OpsCopter\DB\ProjectBundle\ProjectTypeManager;
use OpsCopter\DB\ProjectBundle\Tests\Fixtures\DummyProjectProvider;
use OpsCopter\DB\ProjectBundle\Validator\Constraints\ProjectUrl;
use OpsCopter\DB\ProjectBundle\Validator\Constraints\ProjectUrlValidator;
use Symfony\Component\Validator\Tests\Constraints\AbstractConstraintValidatorTest;
use Symfony\Component\Validator\Validation;

class ProjectUrlValidatorTest extends AbstractConstraintValidatorTest {

    protected function getApiVersion()
    {
        return Validation::API_VERSION_2_5;
    }

    protected function createValidator()
    {
        return new ProjectUrlValidator();
    }

    public function getMockManager() {
        return $this->getMockBuilder('OpsCopter\DB\ProjectBundle\ProjectTypeManager')
            ->getMock();
    }

    public function testValidateValidUrlAgainstAnyProvider() {
        $provider = new DummyProjectProvider();
        $manager = new ProjectTypeManager(array($provider));
        $this->validator->setManager($manager);

        $this->validator->validate('http://google.com', new ProjectUrl());
        $this->assertNoViolation();
    }

    public function testValidateInvalidUrlAgainstAnyProvider() {
        $manager = new ProjectTypeManager();
        $this->validator->setManager($manager);

        $this->validator->validate('http://google.com', new ProjectUrl());
        $this->buildViolation('The url: %url% does not match a known provider.')
            ->setParameter('%url%', 'http://google.com')
            ->assertRaised();
    }

    public function testValidateValidUrlAgainstSingleProvider() {
        $provider = new DummyProjectProvider('dummy', '/' . preg_quote('http://google.com', '/') . '/');
        $this->validator->setManager(new ProjectTypeManager(array($provider)));

        $constraint = new ProjectUrl();
        $constraint->provider = 'dummy';
        $this->validator->validate('http://google.com', $constraint);
        $this->assertNoViolation();
    }

    public function testValidateInvalidUrlAgainstSingleProvider() {
        $provider = new DummyProjectProvider('dummy', '/^$/');
        $this->validator->setManager(new ProjectTypeManager(array($provider)));

        $constraint = new ProjectUrl();
        $constraint->provider = 'dummy';
        $this->validator->validate('http://google.com', $constraint);
        $this->buildViolation('The url: %url% does not match a known provider.')
            ->setParameter('%url%', 'http://google.com')
            ->assertRaised();
    }
}
