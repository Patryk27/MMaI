import Dispatcher from '../../base/Dispatcher';
import requests from './analytics/requests';

Dispatcher.register('backend--views--analytics--requests', requests);
