import Dispatcher from '../../base/Dispatcher';
import createEdit from './pages/create-edit';
import index from './pages/index';

Dispatcher.register('backend--views--pages--create', createEdit);
Dispatcher.register('backend--views--pages--edit', createEdit);
Dispatcher.register('backend--views--pages--index', index);
