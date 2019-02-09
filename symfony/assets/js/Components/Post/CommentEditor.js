import React from 'react';
import CssBaseline from '@material-ui/core/CssBaseline';
import Paper from '@material-ui/core/Paper';
import withStyles from '@material-ui/core/styles/withStyles';
import axios from './../../axios';
import CircularProgress from '@material-ui/core/CircularProgress';
import Typography from '@material-ui/core/Typography';
import Parser from 'html-react-parser';
import CKEditor from "@ckeditor/ckeditor5-react";
import ClassicEditor from "@ckeditor/ckeditor5-build-classic";
import Button from "@material-ui/core/Button/Button";
import { withSnackbar } from 'notistack';

const styles = theme => ({
    footer: {
        marginTop: '20px',
        borderRadius: '0',
        fontSize: '1.5rem',
        color: '#fff',
        width: '60%',
    },
});

class CommentEditor extends React.Component {
    constructor(props) {
        super(props);

        this.state = {
            content: ''
        }
    }

    componentDidMount = () => {

    };

    render() {
        const classes = this.props.classes;
        return (
            <React.Fragment>
                <CssBaseline/>
                <Paper className={classes.footer}>
                    <CKEditor
                        editor={ClassicEditor}
                        onInit={editor => {
                            // You can store the "editor" and use when it is needed.
                            console.log('Editor is ready to use!', editor);
                        }}
                        data={this.state.content}
                        onChange={(event, editor) => {
                            let data = editor.getData();
                            this.setState({
                                content: data
                            });
                        }
                        }
                    />
                    <Button
                        fullWidth
                        variant="raised"
                        color="secondary"
                        onClick={() => {
                            if (this.state.content !== '') {
                                axios.post('/api/v1/comment', {
                                    content: this.state.content,
                                    post: this.props.post,
                                    parentComment: this.props.parrentComment
                                }, {
                                    headers: {'Authorization': 'Bearer ' + localStorage.getItem('token')}
                                }).then((response) => {
                                    this.props.getPostComment();
                                    this.props.onPresentSnackbar('success', 'Dodano komentarz')
                                }).catch((e) => {
                                    this.props.onPresentSnackbar('error', "coś poszło nie tak")
                                })
                            }
                        }}
                    >
                        Dodaj komentarz
                    </Button>
                </Paper>
            </React.Fragment>
        );
    }
}

export default withStyles(styles)(withSnackbar(CommentEditor));