import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import CircularProgress from '@material-ui/core/CircularProgress';
import Typography from '@material-ui/core/Typography';
import Parser from 'html-react-parser';
import CommentEditor from './CommentEditor';
import PostComment from './Comment/PostComment';
import Button from "@material-ui/core/Button/Button";

const styles = theme => ({
    footer: {
        marginTop: '20px',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#fff'
    },
    singlePost__header: {
        textAlign: 'center'
    },
    iframe: {
        width: '100%',
        height: '80vh',
    },
});

class SinglePost extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            post: {},
            loading: true,
            error: false,
            comments: [],
            limit: 10,
            page: 1,
        };
        console.log(this.props.match.params.id)
    }

    componentDidMount = () => {
        axios.get(`/api/v1/post/${this.props.match.params.id}`)
            .then((response) => {
                this.setState({
                    post: response.data,
                    loading: false
                })
            })
            .catch((e) => {
                this.setState({
                    error: true,
                    loading: false
                })
            })
    };

    getPostComment = () => {
        axios.get(`/api/comment/${this.props.match.params.id}?page=${this.state.page}&limit=${this.state.limit}`)
            .then((response) => {
                this.setState({
                    comments: response.data.data
                })
            })
    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                {this.state.loading === true && <CircularProgress size={50}/>}
                {this.state.error === false && <React.Fragment>
                    {this.state.loading === false && <React.Fragment>
                        <Paper className={classes.footer}>
                            <Typography component="h2" variant="display4" className={classes.singlePost__header}>
                                {this.state.post.title}
                            </Typography>
                            <Typography gutterBottom noWrap className={classes.iframe}>
                                {Parser(this.state.post.content)}
                            </Typography>
                        </Paper>
                        <CommentEditor post={this.props.match.params.id} getPostComment={this.getPostComment}/>
                        <PostComment comments={this.state.comments} post={this.props.match.params.id}
                                     getPostComment={this.getPostComment}/>
                        <Paper className={classes.footer}>
                            <Button
                                fullWidth
                                variant="raised"
                                color="secondary"
                                onClick={() => {
                                    let limit = this.state.limit;
                                    limit += 10;
                                    this.setState({
                                        limit
                                    }, () => {
                                        this.getPostComment();
                                    });
                                }}
                            >
                                Wiecej komentarzy
                            </Button>
                        </Paper>
                    </React.Fragment>}
                </React.Fragment>}
                {this.state.error === true && <React.Fragment>
                    <Typography component="h2" variant="display4" className={classes.singlePost__header}>
                        Coś poszło nie tak
                    </Typography>
                </React.Fragment>}
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(SinglePost);