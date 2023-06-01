<?php

namespace LiveIntent;

class ResourceServiceOptions
{
    public bool $keepRawResponse;

    public function withRawResponse(): void
    {
        $this->keepRawResponse = true;
    }
}
