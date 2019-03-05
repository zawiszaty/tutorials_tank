import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Grid from "@material-ui/core/Grid";
import ChangeUserNameForm from "../../components/User/Panel/ChangeUserNameForm";
import {connect} from "react-redux";
import ChangeUserEmailForm from "../../components/User/Panel/ChangeUserEmailForm";
import ChangeUserPasswordForm from "../../components/User/Panel/ChangeUserPasswordForm";
import ChangeUserAvatarForm from "../../components/User/Panel/ChangeUserAvatarForm";

class UserPanel extends Component {
    render() {
        return (
            <Grid container alignContent="center" justify="center"  spacing={24}>
                <Grid item xs={12} md={12} justify="center" alignContent="center">
                    <ChangeUserNameForm user={this.props.user[0]}/>
                </Grid>
                <Grid item xs={12} md={12} justify="center" alignContent="center">
                    <ChangeUserEmailForm user={this.props.user[0]}/>
                </Grid>
                <Grid item xs={12} md={12} justify="center" alignContent="center">
                    <ChangeUserPasswordForm/>
                </Grid>
                <Grid item xs={12} md={12} justify="center" alignContent="center">
                    <ChangeUserAvatarForm user={this.props.user[0]}/>
                </Grid>
            </Grid>
        );
    }
}

UserPanel.propTypes = {};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

export default connect(mapStateToProps)(UserPanel);