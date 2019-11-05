import React from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import '@fortawesome/fontawesome-free-solid';

export default (props) => <span className="text-teal-darker sort-handler cursor-pointer mr-2" title={props.title || 'Sort'}>
    <FontAwesomeIcon icon='sort'/>
</span>