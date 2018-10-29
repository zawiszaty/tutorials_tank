import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import CircularProgress from '@material-ui/core/CircularProgress';
import Typography from '@material-ui/core/Typography';
import Parser from 'html-react-parser';

const styles = theme => ({
    footer: {
        marginTop: '20px',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#fff'
    },
    singlePost__header: {
        textAlign: 'center'
    }
});

class SinglePost extends React.Component {
    constructor(props) {
        super(props);
        this.state = {
            post: {},
            loading: true,
            error: false,
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

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <Paper className={classes.footer}>
                    {this.state.loading === true && <CircularProgress size={50}/>}
                    {this.state.error === false && <React.Fragment>
                        {this.state.loading === false && <React.Fragment>
                            <Typography component="h2" variant="display4" className={classes.singlePost__header}>
                                {this.state.post.title}
                            </Typography>
                            <Typography gutterBottom noWrap>
                                {Parser(this.state.post.content)}
                            </Typography>
                        </React.Fragment>}
                    </React.Fragment>}
                    {this.state.error === true && <React.Fragment>
                        <Typography component="h2" variant="display4" className={classes.singlePost__header}>
                            Coś poszło nie tak
                        </Typography>
                    </React.Fragment>}
                </Paper>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(SinglePost);