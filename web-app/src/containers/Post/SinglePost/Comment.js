import React, {Component} from 'react';
import PropTypes from 'prop-types';
import Button from '@material-ui/core/Button';
import Paper from '@material-ui/core/Paper';
import Typography from '@material-ui/core/Typography';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../../axios/axios';
import CircularProgress from "@material-ui/core/CircularProgress";
import renderHTML from 'react-render-html';
import {Link as RouterLink} from "react-router-dom";
import {connect} from "react-redux";
import Avatar from "@material-ui/core/es/Avatar/Avatar";
import Grid from "@material-ui/core/Grid";
import AddComment from "./AddComment";
import DeleteCommentModal from "./DeleteCommentModal";

function TabContainer({children, dir}) {
    return (
        <Typography component="div" dir={dir} style={{padding: 8 * 3}}>
            {children}
        </Typography>
    );
}

const styles = theme => ({
    main: {
        width: 'auto',
        display: 'block', // Fix IE 11 issue.
        marginLeft: theme.spacing.unit * 3,
        marginRight: theme.spacing.unit * 3,
        [theme.breakpoints.up(400 + theme.spacing.unit * 3 * 2)]: {
            width: "80%",
            marginLeft: 'auto',
            marginRight: 'auto',
        },
    },
    paper: {
        marginTop: theme.spacing.unit * 8,
        display: 'flex',
        flexDirection: 'row',
        alignItems: 'center',
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    paper__content: {
        marginTop: theme.spacing.unit * 8,
        padding: `${theme.spacing.unit * 2}px ${theme.spacing.unit * 3}px ${theme.spacing.unit * 3}px`,
    },
    paper__content__oder: {
        marginTop: theme.spacing.unit * 8,
        minHeight: "100vh",
    },
    root: {
        backgroundColor: theme.palette.background.paper,
        width: 500,
    },
    body__iframe: {
        height: "100vh",
        width: "100%",
    },
    avatar: {
        width: "5em",
        height: "5em",
    },
    user: {
        display: "flex",
        textAlign: "center",
        flexDirection: 'column',
        alignItems: 'center',
        justifyContent: 'center',
    },
    comment: {
        marginTop: theme.spacing.unit * 8,
        display: "flex",
        textAlign: "center",
        flexDirection: 'column',
        alignItems: 'center',
    },
});

class Comment extends Component {
    constructor(props) {
        super(props);
        this.state = {
            comments: [],
            loading: true,
            error: false,
        };
    }


    componentDidMount = () => {
        this.getAllComments();
    };

    getAllComments = () => {
        this.setState({
            loading: true
        });
        axios.get(`/api/v1/comments/${this.props.postId}`).then((e) => {
            this.setState({
                comments: e.data.data,
                loading: false
            });
        })
            .catch((e) => {
                this.setState({
                    error: true,
                    loading: false
                });
            })
    };


    render() {
        const {classes, theme} = this.props;

        return (
            <main className={classes.main}>
                {this.props.user.length !== 0 &&
                <React.Fragment>
                    <AddComment postId={this.props.postId} getAllComments={this.getAllComments}/>
                </React.Fragment>
                }
                <Paper className={classes.comment}>
                    <Typography variant="h3" gutterBottom>
                        Komentarze
                    </Typography>
                </Paper>
                {this.state.loading === true ? <Paper className={classes.paper}>
                        <CircularProgress className={classes.progress} color="secondary"/>
                    </Paper> :
                    <React.Fragment>
                        {this.state.error === false ?
                            <React.Fragment>
                                {this.state.comments.map((comment) => {
                                    return (
                                        <Paper container className={classes.paper}>
                                            <Grid item xs={4} className={classes.user}>
                                                <Avatar src={"http://localhost:9999/" + comment.user.avatar}
                                                        className={classes.avatar}/>
                                                <Typography variant="title">
                                                    {comment.user.username}
                                                </Typography>
                                            </Grid>
                                            <Grid item xs={8}>
                                                {renderHTML(comment.content)}
                                            </Grid>
                                            {this.props.user.length !== 0 &&
                                            <React.Fragment>
                                                {this.props.user[0].name === comment.user.username &&
                                                <DeleteCommentModal id={comment.id}
                                                                    getAllComments={this.getAllComments}/>
                                                }
                                            </React.Fragment>}
                                        </Paper>
                                    )
                                })}
                            </React.Fragment> :
                            <React.Fragment>{}</React.Fragment>
                        }
                    </React.Fragment>
                }
            </main>
        );
    }
}

Comment.propTypes = {
    classes: PropTypes.object.isRequired,
};

const mapStateToProps = (state) => {
    return {
        user: state.user
    }
};

export default connect(mapStateToProps)(withStyles(styles, {withTheme: true})(Comment));