import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../axios';
import CircularProgress from '@material-ui/core/CircularProgress';
import Typography from '@material-ui/core/Typography';
import Parser from 'html-react-parser';
import CKEditor from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Button from "@material-ui/core/Button/Button";
import Avatar from '@material-ui/core/Avatar';
import CommentEditor from "../CommentEditor";

const styles = theme => ({
    footer: {
        marginTop: '20px',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#000',
        width: '60%',
    },
});

class PostComment extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            test: '',
            content: '',
            loading: true,
            error: false,
        };
        console.log(this.state.comments);
    }

    componentDidMount = () => {
        this.props.getPostComment();
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                {this.props.comments.length !== 0 &&
                <React.Fragment>
                    {this.props.comments.map((comment) => {
                            return (
                                <React.Fragment>
                                    <Paper className={classes.footer}>
                                        {comment.id}
                                        <Avatar
                                            alt="avatar"
                                            src={comment.user.avatar}
                                        />
                                        {comment.user.username}
                                        {Parser(comment.content)}
                                        {comment.childrenComment !== undefined &&
                                        <React.Fragment>
                                            {comment.childrenComment.map((childrenComment) => {
                                                return (
                                                    <React.Fragment>
                                                        {childrenComment.id}
                                                        <Avatar
                                                            alt="avatar"
                                                            src={childrenComment.user.avatar}
                                                        />
                                                        {childrenComment.user.username}
                                                        {Parser(childrenComment.content)}
                                                    </React.Fragment>)
                                            })}
                                        </React.Fragment>}
                                        <Button
                                            fullWidth
                                            variant="raised"
                                            color="secondary"
                                            onClick={() => {
                                                axios.get(`/api/comment/children/${comment.id}`)
                                                    .then((response) => {
                                                        comment.childrenComment.push(response.data);
                                                        this.setState({
                                                            test: 1
                                                        })
                                                    })
                                            }}
                                        >
                                            Wiecej komentarzy
                                        </Button>
                                    </Paper>
                                    <CommentEditor post={this.props.post} parrentComment={comment.id}
                                                   getPostComment={this.props.getPostComment}/>
                                </React.Fragment>
                            )
                        }
                    )}
                </React.Fragment>
                }
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(PostComment);