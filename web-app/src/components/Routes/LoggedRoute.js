import {Redirect, Route} from "react-router-dom";
import {store} from "../../store";
import React, {Component} from "react";
import * as PropTypes from "prop-types";

export class LoggedRoute extends Component {
    render() {
        let {component: Component, ...rest} = this.props;
        if (store.getState().user.length !== 0) {
            return (
                <Route {...rest} render={(props) => (
                    <Component {...props} />
                )}/>
            );
        } else {
            return (
                <Route {...rest} render={(props) => (
                    <Redirect to='/'/>
                )}/>
            );
        }
    }
}

LoggedRoute.propTypes = {component: PropTypes.any};