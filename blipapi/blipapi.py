# -*- coding: utf-8 -*-
# $Id$
#
# Blip! (http://blip.pl) communication library.
# Author: Marcin Sztolcman <marcin /at/ urzenia /dot/ net>
# Version: 0.02.10
# Copyright: (r) 2009 Marcin Sztolcman
# License: http://opensource.org/licenses/gpl-license.php GNU Public License v.2

import copy
import httplib
import socket
import time

try:
    from oauth import oauth
except ImportError:
    import warnings
    warnings.warn ('Missing OAuth module')

_blipapi_json_decode = None
try:
    import json
    _blipapi_json_decode = json.loads
except ImportError:
    try:
        import cjson
        _blipapi_json_decode = cjson.decode
    except ImportError:
        try:
            import simplejson
            _blipapi_json_decode = simplejson.loads
        except ImportError:
            pass

from _utils import arr2qstr



class BlipApiError (Exception):
    pass

class BlipApi (object):
    VERSION = '0.02.10'

    api_uri = 'api.blip.pl'

    ## debug
    def __debug_get (self):
        return self._debug
    def __debug_set (self, level):
        if not type (level) is int or level < 0:
            level = 0
        self._debug = level

        ## nie zawsze _ch bedzie ustawione, bledy wtedy pomijamy
        try:
            self._ch.set_debuglevel (level)
        except:
            pass
    def __debug_del (self):
        self.debug = 0
    debug = property (__debug_get, __debug_set, __debug_del)

    ## parser
    def __parser_get (self):
        return self._parser
    def __parser_set (self, parser):
        if not callable (parser):
            raise BlipApiError ('Given parser is not callable!')
        self._parser = parser
    def __parser_del (self):
        raise BlipApiError ('Cannot delete parser!')
    parser = property (__parser_get, __parser_set, __parser_del)

    ## referer
    def __referer_get (self):
        return self._referer
    def __referer_set (self, referer):
        self._referer = str (referer or '')
    def __referer_del (self):
        self._referer = ''
    referer = property (__referer_get, __referer_set, __referer_del)

    ## rpm
    def __rpm_get (self):
        return self._rpm
    def __rpm_set (self, rpm):
        self._rpm = int (rpm)
    def __rpm_del (self):
        self._rpm = 0
    rpm = property (__rpm_get, __rpm_set, __rpm_del)

    ## uagent
    def __uagent_get (self):
        return self._uagent
    def __uagent_set (self, uagent):
        self._uagent = str (uagent or '')
    def __uagent_del (self):
        self._uagent = ''
    uagent = property (__uagent_get, __uagent_set, __uagent_del)

    def _shaperd (self):
        if not self._rpm:
            return True

        ts = time.time ()
        self._shaperd_data[0] += 1

        if (ts - self._shaperd_data[1]) >= 60:
            self.shaperd_reset (_counter = 1)
            return True

        if self._shaperd_data[0] > self._rpm:
            print self._shaperd_data
            return False

        return True

    def shaperd_reset (self, _counter = 0):
        self._shaperd_data = [_counter, time.time ()]

    def __init__ (self, oauth_consumer=None, oauth_token=None, dont_connect=False):
        self._ch                = None
        self._debug             = 0
        self._format            = 'application/json'
        self._headers           = {
            'Accept':       self._format,
            'X-Blip-API':   '0.02',
        }
        self._oauth_consumer    = oauth_consumer
        self._oauth_token       = oauth_token
        self._referer           = ''
        self._rpm               = 0
        self._uagent            = 'BlipApi.py/0.02.10 (http://blipapi.googlecode.com)'

        if callable (_blipapi_json_decode):
            self.parser = _blipapi_json_decode

        if not dont_connect:
            self.connect ()

    def connect (self):
        self._ch = httplib.HTTPConnection (self.api_uri, port=httplib.HTTP_PORT)
        if self._ch:
            self.shaperd_reset ()
            self._ch.set_debuglevel (self.debug)

    def __call__ (self, fn, *args, **kwargs):
        return getattr (self, fn) (*args, **kwargs)

    def __execute (self, method, args, kwargs):
        ## build request data
        req_data = method (*args, **kwargs)

        if 'just_return' in req_data:
            return req_data['just_return']

        ## play with request headers
        headers = copy.deepcopy (self._headers)
        if self.uagent:
            headers['User-Agent'] = self.uagent
        if self.referer:
            headers['Referer']    = self.referer

        headers['Content-Type'] = 'multipart/form-data'
        if 'boundary' in req_data:
            headers['Content-Type'] += '; boundary="' + req_data['boundary'] + '"'
        if 'headers' in req_data:
            headers.update (req_data['headers'])

        req_body = req_data.get ('data', '')
        headers['Content-Length'] = len (req_body)

        if not self._ch:
            self.connect ()

        ## build url
        url     = 'http://%s%s' % (self.api_uri, req_data['url'])

        ## play with parameters
        params  = req_data.get ('params', None)
        if not req_data.get ('params_all', False) and params is not None:
            for k, v in req_data['params'].items ():
                if not v:
                    del req_data['params'][k]

        ## sign request, if we have oauth data
        if self._oauth_token and self._oauth_consumer:
            oauth_request = oauth.OAuthRequest.from_consumer_and_token (
                self._oauth_consumer,
                token       = self._oauth_token,
                http_method = req_data['method'].upper(),
                http_url    = url,
                parameters  = params,
            )
            oauth_request.sign_request (
                oauth.OAuthSignatureMethod_HMAC_SHA1(),
                self._oauth_consumer,
                token = self._oauth_token
            )
            oauth_headers = oauth_request.to_header()
            headers.update(oauth_headers)

        ## add query string
        if params is not None:
            url += '?' + arr2qstr (params, True)

        ## go!
        try:
            shaperd = self._shaperd ()
            if not shaperd:
                raise BlipApiError ('Too many requests')

            self._ch.request (req_data['method'].upper (), url, body=req_body, headers=headers)
        except socket.error, (errno, error):
            self._ch = None
            raise BlipApiError ('Connection error: [%d] %s' % (errno, error))
        else:
            response    = self._ch.getresponse ()

        body_parsed = False
        body        = response.read ()
        if response.status in (200, 201, 204):
            ## parser errors need to be handled in higher level (by blipapi.py user)
            if body:
                body    = self.parser (body)
            else:
                body    = []
            body_parsed = True

        ## hack na 302 i przekierowanie na strone gg
        elif response.status == 302 and response.getheader ('Location', '').startswith ('http://czydziala.gadu-gadu.pl/blip'):
            raise BlipApiError ('Service Unavailable')

        return dict (
            headers     = dict ((k.lower (), v) for k, v in response.getheaders ()),
            body        = body,
            body_parsed = body_parsed,
            status_code = response.status,
            status_body = response.reason,
        )

    def _import (self, name):
        mod = __import__(name)
        components = name.split('.')
        for comp in components[1:]:
            mod = getattr(mod, comp)
        return mod

    def __getattr__ (self, fn):
        if '_' not in fn:
            raise AttributeError ('Command not found: %s.' % fn)

        module_name, method = fn.split ('_', 1)

        try:
            module = self._import ('blipapi.' + module_name)
            method = getattr (module, method)
        except Exception, e:
            raise AttributeError ('Command not found: %s.' % fn)

        if not callable (method):
            raise AttributeError ('Command not found: %s.' % fn)

        return lambda *args, **kwargs: self.__execute (method, args, kwargs)

