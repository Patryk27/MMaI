import Dispatcher from '../../base/Dispatcher';
import createEdit from './pages/create-edit';

Dispatcher.register('backend--pages--pages--create', createEdit);
Dispatcher.register('backend--pages--pages--edit', createEdit);