import React from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import '@fortawesome/fontawesome-free-solid';

export default (props) => <span className="text-red-dark" title={props.title || ''}>
    <FontAwesomeIcon icon='trash'/>
</span>