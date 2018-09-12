import Dispatcher from '../../base/Dispatcher';
import createEdit from './pages/create-edit';
import index from './pages/index';

Dispatcher.register('backend--pages--pages--create', createEdit);
Dispatcher.register('backend--pages--pages--edit', createEdit);
Dispatcher.register('backend--pages--pages--index', index);
