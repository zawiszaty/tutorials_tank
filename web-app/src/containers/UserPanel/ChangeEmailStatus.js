import React, {Component} from 'react';
import PropTypes from 'prop-types';
import axios from "../../axios/axios";

class ChangeEmailStatus extends Component {
    constructor(props) {
        super(props);
        this.state = {}
    }


    confirmChangeEmail = () => {
        axios.patch(`/api/v1/user/change/email/status?type=confirm&&token=${this.props.match.params.token}`)
    };

    discardChangeEmail = (oldEmail) => {
        axios.patch(`/api/v1/user/change/email/status?type=discard&&token=${this.props.match.params.token}&&oldEmail=${oldEmail}`)
    };

    render() {
        const params = new URLSearchParams(this.props.location.search);
        if (params.get('type') === 'confirm') {
            this.confirmChangeEmail();
        } else {
            this.discardChangeEmail(params.get('oldEmail'));
        }
        return (
            <div>
                {this.props.match.params.token}
                {params.get('type')}
            </div>
        );
    }
}

ChangeEmailStatus.propTypes = {};

export default ChangeEmailStatus;