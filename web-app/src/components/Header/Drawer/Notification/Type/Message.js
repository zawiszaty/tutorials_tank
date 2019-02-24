import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Typography from "@material-ui/core/Typography";
import {withRouter} from "react-router-dom";

class Comment extends Component {
    constructor(props) {
        super(props);
        this.state = {
            content: JSON.parse(props.content)
        }
    }

    render() {
        return (
            <div onClick={() => {
                this.props.history.push(`/wiadomosci/${this.state.content.sender.id}`)
            }}>
               Uzytkownik {this.state.content.sender.username} <br/>
                wysłał ci wiadomość <br/>
            </div>
        );
    }
}

Comment.propTypes = {};

export default withRouter(Comment);