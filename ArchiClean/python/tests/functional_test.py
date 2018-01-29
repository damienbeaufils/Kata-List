import pytest
from flask import json

from app.game import api
from tests.base_fixtures import glider, lwss, penta, pulsar
from tests.evolved_fixtures import evolved_glider, evolved_lwss, evolved_penta, evolved_pulsar


class ApiTest:
    def setup_method(self):
        api.config['TESTING'] = True
        self.api = api.test_client(self)

    @pytest.mark.parametrize('template_name, base', [
        ('glider', glider),
        ('lwss', lwss),
        ('penta', penta),
        ('pulsar', pulsar)
    ])
    def test_get(self, template_name, base):
        # when
        response = self.api.get('/grid?template=%s' % template_name, content_type='application/json')

        # then
        assert json.loads(response.get_data(as_text=True)) == base

    @pytest.mark.parametrize('template_name, base, evolved', [
        ('glider', glider, evolved_glider),
        ('lwss', lwss, evolved_lwss),
        ('penta', penta, evolved_penta),
        ('pulsar', pulsar, evolved_pulsar)
    ])
    def test_post(self, template_name, base, evolved):
        # when
        response = self.api.post('/grid', data=json.dumps(base), content_type='application/json')

        # then
        assert json.loads(response.get_data(as_text=True)) == evolved
