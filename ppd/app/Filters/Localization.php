<?php

namespace App\Filters;

use CodeIgniter\HTTP\RequestInterface;
use CodeIgniter\HTTP\ResponseInterface;
use CodeIgniter\Filters\FilterInterface;

class Localization implements FilterInterface
{
    public function before(RequestInterface $request, $arguments = null)
    {
        $supportedLocales = $request->config->supportedLocales;
        if (sizeof($supportedLocales) > 1) {

            if ($request->uri->getTotalSegments() > 0 && !in_array($request->uri->getSegment(1), $supportedLocales)) {

                // TODO: check for exceptions (non-localized routes)
                return redirect()->to($request->config->defaultLocale);
            }
        }
    }

    //--------------------------------------------------------------------

    public function after(RequestInterface $request, ResponseInterface $response, $arguments = null)
    {
        // Do something here
        // var_dump($response);
    }
}
