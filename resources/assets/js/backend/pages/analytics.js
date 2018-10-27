import Dispatcher from '../../base/Dispatcher';
import requests from './analytics/requests';

Dispatcher.register('backend--pages--analytics--requests', requests);
