<?php

namespace LiveIntent;

class ResourceServiceOptions
{
    public bool $keepRawResponse;

    public bool $manuallyHandleRequestErrors;

    public function __construct()
    {
        $this->keepRawResponse = false;
        $this->manuallyHandleRequestErrors = false;
    }

    public function withRawResponse(): void
    {
        $this->keepRawResponse = true;
    }

    public function withManuallyHandledRequestErrors(): void
    {
        $this->manuallyHandleRequestErrors = true;
    }


}
