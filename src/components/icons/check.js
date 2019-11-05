import React from 'react';
import FontAwesomeIcon from '@fortawesome/react-fontawesome';
import '@fortawesome/fontawesome-free-solid';

export default (props) => <span className="text-green-dark" title={props.title || ''}>
    <FontAwesomeIcon icon='star'/>
</span>