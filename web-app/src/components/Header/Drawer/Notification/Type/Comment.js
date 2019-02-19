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
                this.props.history.push(`/post/${this.state.content.post.slug}`)
            }}>
               Uzytkownik {this.state.content.sender.username} <br/>
                skomentował twój post <br/>
                "{this.state.content.post.title}"
            </div>
        );
    }
}

Comment.propTypes = {};

export default withRouter(Comment);