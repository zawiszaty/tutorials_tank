import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Comment from "./Type/Comment";

class NotificationSwitch extends Component {
    constructor(props) {
        super(props);
    }

    render() {
        if (this.props.notification.type === 'comment') {
            return (
                <div>
                    <Comment content={this.props.notification.content}/>
                </div>
            );
        }
    }
}

NotificationSwitch.propTypes = {};

export default NotificationSwitch;